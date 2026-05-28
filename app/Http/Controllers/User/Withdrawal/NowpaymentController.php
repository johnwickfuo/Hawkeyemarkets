<?php

namespace App\Http\Controllers\User\Withdrawal;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\WithdrawalMethod;
use App\Services\NowpaymentService;
use Illuminate\Http\Request;
use Log;

class NowpaymentController extends Controller
{
    public function index(Request $request)
    {
        $withdrawal_request = $request->withdrawal_request;
        if (!$withdrawal_request) {
            return redirect()->route('user.withdrawals.index')->with('error', __("Invalid Withdrawal Attempt"));
        }
        try {
            $withdrawal_request = decrypt(urldecode($withdrawal_request));
        } catch (\Exception $e) {
            return redirect()->route('user.withdrawals.index')->with('error', __("Invalid Withdrawal Attempt"));
        }

        $amount = $withdrawal_request['amount'] ?? null;
        $withdrawal_method_id = $withdrawal_request['withdrawal_method_id'] ?? null;

        if (!$amount || !$withdrawal_method_id) {
            return redirect()->route('user.withdrawals.index')->with('error', __("Invalid Withdrawal Attempt"));
        }

        //get withdrawal method
        $method = WithdrawalMethod::where('id', $withdrawal_method_id)->where('pay', 'nowpayments')->first();
        if (!$method) {
            return redirect()->route('user.withdrawals.index')->with('error', __("Invalid Withdrawal Method"));
        }

        $page_title = __("Withdraw via $method->name");
        $template = config('site.template');
        return view("templates.$template.blades.user.withdrawals.auto.nowpayments", compact(
            'page_title',
            'method',
            'withdrawal_request'
        ));
    }

    // withdraw
    public function withdraw(Request $request)
    {
        $request->validate([
            'withdrawal_currency' => 'required|string',
            'amount' => 'required|numeric',
            'wallet_address' => 'required|string',
        ]);

        $withdrawal_currency = $request->withdrawal_currency;
        $amount = $request->amount;
        $wallet_address = $request->wallet_address;

        //get withdrawal method
        $method = WithdrawalMethod::where('pay', 'nowpayments')->first();
        if (!$method) {
            return redirect()->route('user.withdrawals.new')->with('error', __("Invalid Withdrawal Method"));
        }

        //check if user has enough balance
        $user = auth()->user();
        if ($user->balance < $amount) {
            return redirect()->route('user.withdrawals.index')->with('error', __("Insufficient Balance"));
        }

        // fees
        $min_withdrawal = getSetting('min_withdrawal');
        $max_withdrawal = getSetting('max_withdrawal');
        $fee_percent = getSetting('withdrawal_fee');
        $website_currency = getSetting('currency');

        $fee_amount = $amount * $fee_percent / 100;
        $amount_payable = $amount - $fee_amount;

        // make sure the amount is between the min and max
        if ($amount > $max_withdrawal) {
            return response()->json([
                'status' => 'error',
                'message' => __("Maximum withdrawal amount is :amount :currency", ['amount' => $max_withdrawal, 'currency' => $website_currency])
            ], 422);
        }

        if ($amount < $min_withdrawal) {
            return response()->json([
                'status' => 'error',
                'message' => __("Minimum withdrawal amount is :amount :currency", ['amount' => $max_withdrawal, 'currency' => $website_currency])
            ], 422);
        }


        //check wallet regex
        $currencies = json_decode($method->payment_information, true);

        $currency_information = [];
        foreach ($currencies as $currency) {
            if ($currency['code'] == $withdrawal_currency) {
                $currency_information = $currency;
                break;
            }
        }

        if (count($currency_information) == 0) {
            return response()->json([
                'status' => 'error',
                'message' => __("Invalid Withdrawal Method")
            ], 422);
        }

        $regex = $currency_information['wallet_regex'] ?? null;
        if (!$regex) {
            return response()->json([
                'status' => 'error',
                'message' => __("Invalid Withdrawal Method")
            ], 422);
        }

        //add delimiter to regex
        $regex = "/" . $regex . "/";

        if (!preg_match($regex, $wallet_address)) {
            return response()->json([
                'status' => 'error',
                'message' => __("Invalid Wallet Address")
            ], 422);
        }

        //convert to nowpayments fiat
        $converted_to_usd = rateConverter($amount_payable, getSetting('currency'), 'USD', 'npw');
        $nowpayment_supported_fiat_amount = $converted_to_usd['converted_amount'];


        // get the estimated amount
        $nowpayment = new NowpaymentService();
        $estimate = $nowpayment->FiatToNowpayment($nowpayment_supported_fiat_amount, getSetting('currency'), $withdrawal_currency);

        if (!$estimate['status']) {
            return response()->json([
                'status' => 'error',
                'message' => $estimate['message'],
            ], 422);
        }

        $converted_amount = $estimate['data']['estimated_amount'];

        $balances = $nowpayment->getBalance();

        if (!$balances['status']) {
            return response()->json([
                'status' => 'error',
                'message' => $balances['message'],
            ], 422);
        }

        $availabe_balance = $balances['data'][strtolower($withdrawal_currency)]['amount'] ?? 0;

        if ($availabe_balance < $converted_amount) {
            return response()->json([
                'status' => 'error',
                'message' => __("Insufficient Balance on payment processor")
            ], 422);
        }

        // make a charge to nowpayment
        $ref = \Str::orderedUuid();
        $ipn_callback_url = route('api.v1.webhooks.nowpayments.withdrawal', ['transaction_reference' => $ref]);
        $payout = $nowpayment->createPayout($wallet_address, $withdrawal_currency, $converted_amount, $ref, $ipn_callback_url);


        if (!$payout['status']) {
            return response()->json([
                'status' => 'error',
                'message' => $payout['message'],
            ], 422);
        }

        $payout_data = $payout['data'];
        $batch_withdrawal_id = $payout_data['id'];
        $structured_data = [
            'transaction_hash' => null,
            'wallet_address' => $wallet_address,
            'currency' => $withdrawal_currency,
            'network' => $currency_information['network'] ?? null,
        ];

        // verify payout
        $verify_payout = $nowpayment->verifyPayout($batch_withdrawal_id);
        if (!$verify_payout['status']) {
            return response()->json([
                'status' => 'error',
                'message' => $verify_payout['message'],
            ], 422);
        }

        // calculate exchange rate
        $rate = $converted_amount / $amount_payable;

        // debit user
        $user->refresh();
        $user->decrement('balance', $amount);

        $withdrawal = new Withdrawal();
        $withdrawal->user_id = auth()->id();
        $withdrawal->withdrawal_method_id = $method->id;
        $withdrawal->amount = $amount;
        $withdrawal->converted_amount = $converted_amount;
        $withdrawal->fee_percent = $fee_percent;
        $withdrawal->fee_amount = $fee_amount;
        $withdrawal->amount_payable = $amount_payable;
        $withdrawal->exchange_rate = $rate;
        $withdrawal->transaction_reference = $ref;
        $withdrawal->transaction_hash = null;
        $withdrawal->payment_proof = null;
        $withdrawal->currency = $withdrawal_currency;
        $withdrawal->structured_data = json_encode($structured_data);
        $withdrawal->auto_res_dump = json_encode($payout_data);
        $withdrawal->status = 'pending';
        $withdrawal->save();

        // record new transaction
        recordTransaction($user, $amount, $website_currency, $converted_amount, $withdrawal_currency, $rate, 'debit', 'completed', $ref, "Withdrawal request", $user->balance);

        // notification message
        $title = 'Withdrawal request';
        $message = __('Your withdrawal request of :amount :currency has been submitted successfully', ['amount' => $amount, 'currency' => $website_currency]);
        recordNotificationMessage($user, $title, $message);

        // send email
        $custom_subject = "Withdrawal request submitted";
        $custom_message = "Your withdrawal request has been received and is currently under review by our financial team.";
        sendWithdrawalEmail($custom_subject, $custom_message, $withdrawal);

        return response()->json([
            'status' => 'success',
            'message' => __('Withdrawal request submitted successfully'),
            'redirect' => route('user.withdrawals.index')
        ]);
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
        $withdrawal = Withdrawal::where('transaction_reference', $transaction_reference)->first();

        if (!$withdrawal) {
            Log::error('Nowpayment IPN: Withdrawal not found', ['transaction_reference' => $transaction_reference]);
            return response()->json(['status' => 'error', 'message' => __('Withdrawal not found')], 404);
        }

        // Only process pending or partial payments deposits
        if ($withdrawal->status !== 'pending' && $withdrawal->status !== 'partial_payment') {
            return response()->json(['status' => 'success', 'message' => __('Withdrawal already processed')], 200);
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
        $payment_status = $ipn_data['status'] ?? null;
        if ($payment_status) {
            $payment_status = strtolower($payment_status);
        }



        if ($payment_status === 'finished') {
            $user = $withdrawal->user;
            $transaction_hash = $ipn_data['hash'] ?? null;
            $structured_data = json_decode($withdrawal->structured_data, true);
            $structured_data['transaction_hash'] = $transaction_hash;

            // Record withdrawal update
            $withdrawal->status = 'completed';
            $withdrawal->auto_res_dump = json_encode($ipn_data);
            $withdrawal->transaction_hash = $transaction_hash;
            $withdrawal->structured_data = json_encode($structured_data);
            $withdrawal->save();

            // send withdrawal email
            $custom_subject = "Withdrawal Completed";
            $custom_message = "Withdrawal has been completed find the details below.";
            sendWithdrawalEmail($custom_subject, $custom_message, $withdrawal);

            // Record notification
            $title = "Withdrawal Completed";
            $body = "Withdrawal via " . $withdrawal->withdrawalMethod->name . " has been completed";
            recordNotificationMessage($user, $title, $body);

            return response()->json(['status' => 'success', 'message' => __('Payment processed successfully')], 200);
        }



        if (in_array($payment_status, ['failed', 'rejected'])) {
            $withdrawal->status = 'failed';
            $withdrawal->auto_res_dump = json_encode($ipn_data);
            $withdrawal->save();

            // send deposit email
            $custom_subject = "Withdrawal Failed and Refunded";
            $custom_message = "Your withdrawal request failed and has been refunded to your available balance. If the error persist, contact an admin";
            sendWithdrawalEmail($custom_subject, $custom_message, $withdrawal);
            recordNotificationMessage($withdrawal->user, $custom_subject, $custom_message);

            // refund the user
            $user = $withdrawal->user;
            $user->refresh();
            $user->increment('balance', $withdrawal->amount);

            // record transaction
            recordTransaction($user, $withdrawal->amount, getSetting('currency'), $withdrawal->converted_amount, $withdrawal->currency, $withdrawal->exchange_rate, 'credit', 'completed', $withdrawal->transaction_reference, "Failed Withdrawal refund", $user->balance);

            return response()->json(['status' => 'success', 'message' => __('Payment failed or expired')], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('Payment status updated to: :status', ['status' => $payment_status])
        ], 200);
    }
}
