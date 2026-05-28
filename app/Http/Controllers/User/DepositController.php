<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class DepositController extends Controller
{
    public function index(Request $request)
    {
        $template = config('site.template');
        $page_title = __('Deposits');

        $user_id = auth()->id();

        // Deposits list query
        $deposits_query = Deposit::where('user_id', $user_id);

        // check for search - search by transaction reference or transaction hash
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $deposits_query->where(function ($q) use ($searchTerm) {
                $q->where('transaction_reference', 'like', '%' . $searchTerm . '%')
                    ->orWhere('transaction_hash', 'like', '%' . $searchTerm . '%');
            });
        }

        $deposits = $deposits_query->latest()->paginate(getSetting('pagination', 10));

        // Analytics query
        $stats = Deposit::where('user_id', $user_id)
            ->select([
                DB::raw("COUNT(*) as total_count"),
                DB::raw("SUM(amount) as total_sum"),

                DB::raw("SUM(status = 'pending') as pending_count"),
                DB::raw("SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END) as pending_sum"),

                DB::raw("SUM(status = 'completed') as completed_count"),
                DB::raw("SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as completed_sum"),

                DB::raw("SUM(status = 'failed') as failed_count"),
                DB::raw("SUM(CASE WHEN status = 'failed' THEN amount ELSE 0 END) as failed_sum"),

                DB::raw("SUM(status = 'partial_payment') as partial_count"),
                DB::raw("SUM(CASE WHEN status = 'partial_payment' THEN amount ELSE 0 END) as partial_sum"),
            ])
            ->first();

        // Format into your structure
        $deposits_analytics = [
            'total' => [
                'count' => (int) $stats->total_count,
                'total' => $stats->total_sum,
            ],
            'pending' => [
                'count' => (int) $stats->pending_count,
                'total' => $stats->pending_sum,
            ],
            'completed' => [
                'count' => (int) $stats->completed_count,
                'total' => $stats->completed_sum,
            ],
            'failed' => [
                'count' => (int) $stats->failed_count,
                'total' => $stats->failed_sum,
            ],
            'partial_payment' => [
                'count' => (int) $stats->partial_count,
                'total' => $stats->partial_sum,
            ],
        ];



        // Base query
        $deposits_query = Deposit::where('user_id', $user_id);

        // 1. Status Counts & Totals
        $depositStats = $deposits_query->selectRaw("
            COUNT(*) as total_count,
            SUM(amount) as total_amount,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
            SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END) as pending_total,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count,
            SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as completed_total,
            SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed_count,
            SUM(CASE WHEN status = 'failed' THEN amount ELSE 0 END) as failed_total,
            SUM(CASE WHEN status = 'partial_payment' THEN 1 ELSE 0 END) as partial_count,
            SUM(CASE WHEN status = 'partial_payment' THEN amount ELSE 0 END) as partial_total
        ")->first();

        // 2. Average processing time for completed deposits
        $avg_processing_time = Deposit::where('user_id', $user_id)
            ->where('status', 'completed')
            ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, updated_at)) as avg_seconds')
            ->value('avg_seconds');

        // 3. Fastest and slowest completed deposits
        $fastest_deposit = Deposit::where('user_id', $user_id)
            ->where('status', 'completed')
            ->orderByRaw('TIMESTAMPDIFF(SECOND, created_at, updated_at) ASC')
            ->first();

        $slowest_deposit = Deposit::where('user_id', $user_id)
            ->where('status', 'completed')
            ->orderByRaw('TIMESTAMPDIFF(SECOND, created_at, updated_at) DESC')
            ->first();

        // 4. Fees & Effective Rate
        $fee_stats = Deposit::where('user_id', $user_id)
            ->selectRaw('SUM(fee_amount) as total_fees, AVG(fee_percent) as avg_fee_percent')
            ->first();

        // 5. Deposit Method Analytics
        $method_stats = Deposit::where('user_id', $user_id)
            ->selectRaw('payment_method_id, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method_id')
            ->get();

        // 6. Currency Breakdown
        $currency_stats = Deposit::where('user_id', $user_id)
            ->selectRaw('currency, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('currency')
            ->get();

        // 7. Personal Records
        $largest_deposit = Deposit::where('user_id', $user_id)
            ->orderByDesc('amount')
            ->first();

        $highest_daily_total = Deposit::where('user_id', $user_id)
            ->selectRaw('DATE(created_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->orderByDesc('total')
            ->first();



        return view("templates.$template.blades.user.deposits.index", compact(
            'page_title',
            'deposits',
            'deposits_analytics',
            'avg_processing_time',
            'fastest_deposit',
            'slowest_deposit',
            'fee_stats',
            'method_stats',
            'currency_stats',
            'largest_deposit',
            'highest_daily_total',
        ));
    }


    // View a single deposit
    public function viewDeposit(Request $request)
    {
        $template = config('site.template');
        $page_title = __('View Deposit');
        $transaction_reference = $request->route('transaction_reference');
        $deposit = Deposit::where('transaction_reference', $transaction_reference)->first();
        if (!$deposit) {
            return redirect()->route('user.deposits.index')->with('error', __('Deposit not found'));
        }
        return view("templates.$template.blades.user.deposits.view", compact(
            'page_title',
            'deposit'
        ));
    }


    // new deposit
    public function newDeposit()
    {
        $template = config('site.template');
        $page_title = __('New Deposit');
        $payment_methods = PaymentMethod::where('status', 'enabled')->get();

        return view("templates.$template.blades.user.deposits.new", compact(
            'page_title',
            'payment_methods'
        ));
    }

    //Start Payment
    public function startNewDeposit(Request $request)
    {
        $validated = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric',
        ]);

        $payment_method = PaymentMethod::where('id', $request->payment_method_id)->first();

        $user_payment_details = [
            'amount' => $request->amount,
            'payment_method_id' => $request->payment_method_id
        ];

        session()->put('user_payment_details', $user_payment_details);
        return response()->json([
            'status' => 'success',
            'message' => __('Deposit started successfully'),
            'redirect' => route("user.deposits.new.$payment_method->pay")
        ]);

    }


    // handle manaul payment
    public function manualPayment(Request $request)
    {

        $template = config('site.template');
        $page_title = __('Manual Payment');
        $user_payment_details = session()->get('user_payment_details');

        if (!$user_payment_details) {
            return redirect()->route('user.deposits.new')->with('error', __('You have not selected payment method'));
        }

        $payment_method = PaymentMethod::where('id', $user_payment_details['payment_method_id'])->first();

        if (!$payment_method) {
            return redirect()->route('user.deposits.new')->with('error', __('Payment method not found'));
        }

        // if its ajax request return json
        if (!array_key_exists('expires_at', $user_payment_details)) {
            // calculate fee from getsetting, fee is stored as percent .eg 5, calculate the fee amount,
            $fee_percent = getSetting('deposit_fee');
            $fee_amount = ($user_payment_details['amount'] * $fee_percent) / 100;
            $user_payment_details['fee_amount'] = $fee_amount;
            $user_payment_details['total_amount'] = $user_payment_details['amount'] + $fee_amount;
            $user_payment_details['fee_percent'] = $fee_percent;
            $from_currency = getSetting('currency');
            $info = $payment_method->payment_information;
            if (!is_array($info)) {
                $info = json_decode($info, true);
            }
            $to_currency = $info['currency'] ?? $from_currency;

            $convterted = rateConverter($user_payment_details['total_amount'], $from_currency, $to_currency, 'deposit');

            $user_payment_details['converted_amount'] = $convterted['converted_amount'];
            $user_payment_details['exchange_rate'] = $convterted['exchange_rate'];
            $user_payment_details['currency'] = $to_currency;
            $expiry = getSetting('deposit_expires_at', 7);
            $expiry = (int) $expiry;
            $user_payment_details['expires_at'] = now()->addHours($expiry)->timestamp;
            // store back in session
            session()->put('user_payment_details', $user_payment_details);

        }





        // dd(session()->get('user_payment_details'));


        $deposit = null;
        return view("templates.$template.blades.user.deposits.general", compact(
            'page_title',
            'user_payment_details',
            'payment_method',
            'deposit'
        ));
    }


    // process manual payment
    public function manualPaymentValidate(Request $request)
    {
        $user_payment_details = session()->get('user_payment_details');

        if (!$user_payment_details) {
            return response()->json([
                'status' => 'error',
                'message' => __('You have not selected payment method'),
                'redirect' => route('user.deposits.new')
            ], 422);
        }

        $payment_method = PaymentMethod::where('id', $user_payment_details['payment_method_id'])->first();

        if (!$payment_method) {
            return response()->json([
                'status' => 'error',
                'message' => __('Payment method not found'),
                'redirect' => route('user.deposits.new')
            ], 422);
        }

        // if payment method type is crypto, require transaction hash and proof, else just hash
        if ($payment_method->type == 'crypto') {
            $validated = $request->validate([
                'transaction_hash' => 'required|unique:deposits,transaction_hash|max:255',
                'proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ]);
        } else {
            $validated = $request->validate([
                'proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ]);
        }


        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('deposits', 'public');
        }

        $transaction_reference = Str::orderedUuid();
        $structured_data = null;

        $info = json_decode($payment_method->payment_information, true);


        switch ($payment_method->type) {
            case 'crypto':
                $structured_data = [
                    'transaction_hash' => $request->transaction_hash,
                    'wallet_address' => $info['wallet_address'],
                    'currency' => $info['currency'],
                    'network' => $info['network'],
                ];
                break;

            case 'bank_transfer':
                $structured_data = [
                    'transaction_hash' => $transaction_reference,
                    'bank_name' => $info['bank_name'],
                    'account_holder' => $info['account_holder'],
                    'account_number' => $info['account_number'],
                    'routing_number' => $info['routing_number'],
                    'swift' => $info['swift'],
                ];
                break;

            case 'digital_wallet':
                $structured_data = [
                    'payment_id' => $transaction_reference,
                    'identity_id' => $info['email'] ?? $info['username'] ?? $info['tag'] ?? $info['phone'] ?? $transaction_reference,
                ];
                break;

            default:
                break;
        }

        if ($structured_data) {
            $structured_data = json_encode($structured_data);
        }



        // store deposit
        $deposit = new Deposit();
        $deposit->user_id = auth()->user()->id;
        $deposit->payment_method_id = $user_payment_details['payment_method_id'];
        $deposit->amount = $user_payment_details['amount'];
        $deposit->converted_amount = $user_payment_details['converted_amount'];
        $deposit->fee_percent = $user_payment_details['fee_percent'];
        $deposit->fee_amount = $user_payment_details['fee_amount'];
        $deposit->total_amount = $user_payment_details['total_amount'];
        $deposit->exchange_rate = $user_payment_details['exchange_rate'];
        $deposit->transaction_reference = $transaction_reference;
        $deposit->transaction_hash = $request->transaction_hash ?? $transaction_reference;
        $deposit->payment_proof = $proofPath;
        $deposit->expires_at = now()->addHours(getSetting('deposit_expires_in'))->timestamp;
        $deposit->currency = $user_payment_details['currency'];
        $deposit->structured_data = $structured_data;
        $deposit->auto_res_dump = null;
        $deposit->status = 'pending';
        $deposit->save();


        // remove user payment details from session
        session()->forget('user_payment_details');

        // record notification message
        $title = "Deposit Initiated"; //to be translated later in blade
        $body = __("Your deposit of :amount :currency has been initiated. Please complete the payment within :hours hours.", [
            'amount' => $deposit->total_amount,
            'currency' => $deposit->currency,
            'hours' => getSetting('deposit_expires_in')
        ], auth()->user()->lang);
        recordNotificationMessage(auth()->user(), $title, $body);

        // send email
        $custom_subject = "Deposit Initiated"; //to be translated later in blade
        $custom_message = "We've successfully received your deposit request and it is now being processed."; //to be translated later in blade
        sendDepositEmail($custom_subject, $custom_message, $deposit);

        return response()->json([
            'status' => 'success',
            'message' => __('Deposit created successfully'),
            'redirect' => route('user.deposits.index')
        ]);

    }

    // cancel Manual Payment
    public function manualPaymentCancel()
    {
        // rmeove user payment details from session
        session()->forget('user_payment_details');

        return response()->json([
            'status' => 'success',
            'message' => __('Deposit cancelled successfully'),
            'redirect' => route('user.deposits.index')
        ]);
    }


    // pay now
    public function payNow(Request $request)
    {
        $transaction_referemce = $request->route('transaction_reference');
        $pay = $request->route('pay');

        $user = auth()->user();
        $deposit = Deposit::where('transaction_reference', $transaction_referemce)->where('user_id', $user->id)->first();
        if (!$deposit) {
            return redirect()->route('user.deposits.index')->with('error', __('Deposit not found'));
        }

        // only proceed if the payment status is "pending 
        // if ($deposit->status != 'pending') {
        //     return redirect()->route('user.deposits.index')->with('error', __('Deposit already processed, Please initiate a new payment to proceed'));
        // }

        //just handle manual for now

        $template = config('site.template');
        $dynamic_name_derived_from_pay = ucfirst($pay);
        $page_title = __("$dynamic_name_derived_from_pay Payment");
        $user_payment_details = [
            "amount" => $deposit->amount,
            "payment_method_id" => $deposit->payment_method_id,
            "fee_amount" => $deposit->fee_amount,
            "total_amount" => $deposit->total_amount,
            "fee_percent" => $deposit->fee_percent,
            "converted_amount" => $deposit->converted_amount,
            "exchange_rate" => $deposit->exchange_rate,
            "currency" => $deposit->currency
        ];
        $payment_method = $deposit->paymentMethod;

        return view("templates.$template.blades.user.deposits.general", compact(
            'page_title',
            'user_payment_details',
            'payment_method',
            'deposit'
        ));


    }


    // donwload receipt
    public function downloadReceipt(Request $request)
    {
        $transaction_reference = $request->route('transaction_reference');
        $deposit = Deposit::where('transaction_reference', $transaction_reference)->where('user_id', auth()->user()->id)->first();
        if (!$deposit) {
            return redirect()->route('user.deposits.index')->with('error', __('Deposit not found'));
        }

        $template = config('site.template');

        // // tempaoru view in browser to see design
        // return view("templates.$template.blades.pdf.receipt", compact('deposit'));
        $pdf = PDF::loadView("templates.$template.blades.pdf.receipt", compact('deposit'));
        return $pdf->download("receipt-$transaction_reference.pdf");
    }


    // scope
    public function byScope(Request $request)
    {
        $user = auth()->user();
        $routeParts = explode('.', $request->route()->getName());
        $scopeName = end($routeParts); // e.g., 'approved', 'pending', 'failed'

        $status = $scopeName == 'approved' ? 'completed' : $scopeName;

        $query = Deposit::where('user_id', $user->id)->where('status', $status);

        // Filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('transaction_reference', 'like', "%$search%")
                    ->orWhere('transaction_hash', 'like', "%$search%")
                    ->orWhere('amount', 'like', "%$search%");
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->get('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->get('to_date'));
        }

        if ($request->filled('method_id')) {
            $query->where('payment_method_id', $request->get('method_id'));
        }

        // Total for current filters
        $totalAmount = (clone $query)->sum('amount');

        // Sorting
        $sort = $request->get('sort', 'created_at');
        // direction is only allowed to be 'asc' or 'desc', else default to 'desc'
        $direction = in_array(strtolower($request->get('direction')), ['asc', 'desc']) ? $request->get('direction') : 'desc';
        $query->orderBy($sort, $direction);

        $deposits = $query->paginate(getSetting('pagination', 15))->appends($request->all());

        $payment_methods = PaymentMethod::where('status', 1)->get();

        $template = config('site.template');
        $page_title = __(ucfirst($scopeName) . ' Deposits');

        return view("templates.$template.blades.user.deposits.scope", compact(
            'status',
            'scopeName',
            'page_title',
            'deposits',
            'totalAmount',
            'payment_methods'
        ));
    }

}
