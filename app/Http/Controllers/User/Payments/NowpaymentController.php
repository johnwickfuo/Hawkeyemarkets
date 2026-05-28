<?php

namespace App\Http\Controllers\User\Payments;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use App\Services\NowpaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Log;

class NowpaymentController extends Controller
{
    //index
    public function index()
    {

        $user_payment_details = session()->get('user_payment_details');

        $payment_method = PaymentMethod::where('id', $user_payment_details['payment_method_id'])->first();
        $page_title = __("Pay With :method", ['method' => $payment_method->name]);

        if (!$payment_method) {
            return redirect()->route('user.deposits.index')->with('error', __('Payment method not found'));
        }

        if ($payment_method->pay !== 'nowpayments') {
            return redirect()->route('user.deposits.index')->with('error', __('Payment method not found'));
        }


        $template = config('site.template');

        // dd($user_payment_details);

        return view("templates.$template.blades.user.deposits.payments.nowpayments", compact(
            'page_title',
            'user_payment_details',
            'payment_method'
        ));
    }


    // Nowpayment Validate
    public function nowpaymentsValidate(Request $request)
    {
        $request->validate([
            'payment_currency' => 'required',
        ]);

        $user_deposit_details = session()->get('user_payment_details');
        $payment_method = PaymentMethod::where('id', $user_deposit_details['payment_method_id'])->first();

        if (!$payment_method) {
            return response()->json([
                'status' => 'error',
                'message' => __('Payment method not found'),
            ], 422);
        }

        if ($payment_method->pay !== 'nowpayments') {
            return response()->json([
                'status' => 'error',
                'message' => __('There is issue with your payment, go back to the design page and try again'),
            ], 422);
        }

        $payment_currency = $request->payment_currency;
        $payment_currencies = json_decode($payment_method->payment_information, true);

        $payment_currency_exists = array_filter($payment_currencies, function ($currency) use ($payment_currency) {
            return $currency['code'] === $payment_currency;
        });

        if (empty($payment_currency_exists)) {
            return response()->json([
                'status' => 'error',
                'message' => __('Payment currency not found'),
            ], 422);
        }

        // conver_to_usd, if the website currency is not usd
        $amount = $user_deposit_details['amount'];

        // calculate fees
        $fee_percent = getSetting('deposit_fee');
        $fee_amount = ($amount * $fee_percent) / 100;
        $total_amount = $amount + $fee_amount;


        $converted_to_usd = rateConverter($total_amount, getSetting('currency'), 'USD', 'np');
        $nowpayment_supported_fiat_amount = $converted_to_usd['converted_amount'];


        // get the estimated amount
        $nowpayment = new NowpaymentService();
        $estimate = $nowpayment->FiatToNowpayment($nowpayment_supported_fiat_amount, getSetting('currency'), $payment_currency);

        if (!$estimate['status']) {
            return response()->json([
                'status' => 'error',
                'message' => $estimate['message'],
            ], 422);
        }

        $estimated_amount = $estimate['data']['estimated_amount'];
        $transaction_reference = Str::orderedUuid();

        // create payment
        $payment = $nowpayment->createPayment(
            $estimated_amount,
            $payment_currency,
            $estimated_amount,
            $payment_currency,
            route('api.v1.webhooks.nowpayments.deposit', ['transaction_reference' => $transaction_reference]),
            $transaction_reference
        );

        if (!$payment['status']) {
            return response()->json([
                'status' => 'error',
                'message' => $payment['message'],
            ], 422);
        }

        $payment_info = $payment['data'];

        // compose structured data
        $structured_data = [
            'transaction_hash' => null,
            'wallet_address' => $payment_info['pay_address'],
            'currency' => $payment_currency,
            'network' => $payment_info['network'] ?? null
        ];

        $user = auth()->user();

        // store deposit
        $deposit = new Deposit();
        $deposit->user_id = $user->id;
        $deposit->payment_method_id = $payment_method->id;
        $deposit->amount = $amount;
        $deposit->converted_amount = $estimated_amount;
        $deposit->fee_percent = $fee_percent;
        $deposit->fee_amount = $fee_amount;
        $deposit->total_amount = $total_amount;
        $deposit->exchange_rate = $estimated_amount / $nowpayment_supported_fiat_amount;
        $deposit->transaction_reference = $transaction_reference;
        $deposit->expires_at = strtotime($payment_info['expiration_estimate_date']);
        $deposit->currency = $payment_currency;
        $deposit->structured_data = json_encode($structured_data);
        $deposit->auto_res_dump = json_encode($payment_info);
        $deposit->status = 'pending';
        $deposit->save();

        // refresh the deposit
        $deposit->refresh();

        // send new deposit email
        $custom_subject = "Deposit Initiated";
        $custom_message = "You have initiated a new deposit via " . $payment_method->name . " payment gateway. Your deposit will be processed automatically";
        sendDepositEmail($custom_subject, $custom_message, $deposit);

        //record notification message
        $title = "Deposit Initiated";
        $body = "You have initiated a new deposit via " . $payment_method->name . " payment gateway. Your deposit will be processed automatically";
        recordNotificationMessage($user, $title, $body);

        // clear session
        session()->forget('user_payment_details');

        // redirect to deposit page
        return response()->json([
            'status' => 'success',
            'message' => __('Deposit initiated successfully'),
            'redirect' => route('user.deposits.pay', ['transaction_reference' => $transaction_reference, 'pay' => $payment_method->pay])
        ], 200);
    }





    /**
     * Handle NOWPayments IPN.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function nowpaymentIpnHandler(Request $request)
    {
        $transaction_reference = $request->route('transaction_reference');
        $deposit = Deposit::where('transaction_reference', $transaction_reference)->first();

        if (!$deposit) {
            Log::error('Nowpayment IPN: Deposit not found', ['transaction_reference' => $transaction_reference]);
            return response()->json(['status' => 'error', 'message' => __('Deposit not found')], 404);
        }

        // Only process pending or partial payments deposits
        if ($deposit->status !== 'pending' && $deposit->status !== 'partial_payment') {
            return response()->json(['status' => 'success', 'message' => __('Deposit already processed')], 200);
        }

        $nowpayment_service = new NowpaymentService();
        $received_hmac = $request->header('x-nowpayments-sig');
        $json_data = $request->getContent();

        // Verify Signature
        $verification = $nowpayment_service->verifyIpnSignature($json_data, $received_hmac);

        if ($verification !== true) {
            Log::error('Nowpayment IPN: Signature verification failed', [
                'error' => $verification,
                'transaction_reference' => $transaction_reference
            ]);
            return response()->json(['status' => 'error', 'message' => $verification], 400);
        }

        $ipn_data = json_decode($json_data, true);
        $payment_status = $ipn_data['payment_status'] ?? null;

        $transaction_hash = null;
        if (in_array($payment_status, ['finished', 'partially_paid'])) {
            $nowpayment_service = new NowpaymentService();
            $payment_info = $nowpayment_service->getPaymentStatus($ipn_data['payment_id']);

            if ($payment_info['status']) {
                $transaction_hash = $payment_info['data']['payin_hash'] ?? null;
            }
        }

        if ($payment_status === 'finished') {
            $user = $deposit->user;

            // Record deposit update
            $deposit->status = 'completed';
            $deposit->auto_res_dump = json_encode($ipn_data);
            $deposit->transaction_hash = $transaction_hash;
            $deposit->save();

            // Credit user balance
            $user->refresh();
            $new_balance = $user->balance + $deposit->amount;
            $user->balance = $new_balance;
            $user->save();

            // Record transaction
            $reference = \Str::random(12);
            $description = "Deposit via " . $deposit->paymentMethod->name;
            recordTransaction($user, $deposit->amount, getSetting('currency'), $deposit->converted_amount, $deposit->currency, $deposit->exchange_rate, 'credit', $deposit->status, $reference, $description, $new_balance);

            // send deposit email
            $custom_subject = "Deposit Completed";
            $custom_message = "Deposit has been completed find the details below.";
            sendDepositEmail($custom_subject, $custom_message, $deposit);

            // Record notification
            $title = "Deposit Completed";
            $body = "Deposit via " . $deposit->paymentMethod->name . " has been completed";
            recordNotificationMessage($user, $title, $body);

            return response()->json(['status' => 'success', 'message' => __('Payment processed successfully')], 200);
        }

        if ($payment_status === 'partially_paid') {
            $deposit->status = 'partial_payment';
            $deposit->auto_res_dump = json_encode($ipn_data);
            $deposit->transaction_hash = $transaction_hash;
            $deposit->save();

            // send deposit email
            $custom_subject = "Deposit Partially Paid";
            $custom_message = "Your deposit was not coompleted because the amount you sent is less than the required amount. Please contact an admin";
            sendDepositEmail($custom_subject, $custom_message, $deposit);
            recordNotificationMessage($deposit->user, $custom_subject, $custom_message);

            return response()->json(['status' => 'success', 'message' => __('Payment processed successfully')], 200);
        }

        if (in_array($payment_status, ['failed', 'expired', 'refunded'])) {
            $deposit->status = 'failed';
            $deposit->auto_res_dump = json_encode($ipn_data);
            $deposit->transaction_hash = $transaction_hash;
            $deposit->save();

            // send deposit email
            $custom_subject = "Deposit Failed";
            $custom_message = "Your deposit request failed. Please contact an admin if this was an error.";
            sendDepositEmail($custom_subject, $custom_message, $deposit);
            recordNotificationMessage($deposit->user, $custom_subject, $custom_message);

            return response()->json(['status' => 'success', 'message' => __('Payment failed or expired')], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('Payment status updated to: :status', ['status' => $payment_status])
        ], 200);
    }

}
