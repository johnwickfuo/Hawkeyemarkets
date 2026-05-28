<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\WithdrawalMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class WithdrawalController extends Controller
{
    // scope
    public function byScope(Request $request)
    {
        $user = auth()->user();
        $routeParts = explode('.', $request->route()->getName());
        $scopeName = end($routeParts); // e.g., 'approved', 'pending', 'failed', 'partial'

        $statusMap = [
            'approved' => 'completed',
            'pending' => 'pending',
            'failed' => 'failed',
            'partial' => 'partial_payment'
        ];

        $status = $statusMap[$scopeName] ?? $scopeName;

        $query = Withdrawal::where('user_id', $user->id)->where('status', $status);

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
            $query->where('withdrawal_method_id', $request->get('method_id'));
        }

        // Total for current filters (using amount_payable for withdrawals)
        $totalAmount = (clone $query)->sum('amount_payable');

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = in_array(strtolower($request->get('direction')), ['asc', 'desc']) ? $request->get('direction') : 'desc';
        $query->orderBy($sort, $direction);

        $withdrawals = $query->paginate(getSetting('pagination', 15))->appends($request->all());

        $withdrawal_methods = WithdrawalMethod::where('status', 1)->get();

        $template = config('site.template');
        $page_title = __(ucfirst($scopeName) . ' Withdrawals');

        return view("templates.$template.blades.user.withdrawals.scope", compact(
            'status',
            'scopeName',
            'page_title',
            'withdrawals',
            'totalAmount',
            'withdrawal_methods'
        ));
    }

    public function index(Request $request)
    {
        $template = config('site.template');
        $page_title = __('Withdrawal History');
        $user_id = auth()->id();

        // Withdrawals list query
        $withdrawals_query = Withdrawal::where('user_id', $user_id);

        // search - reference or hash
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $withdrawals_query->where(function ($q) use ($searchTerm) {
                $q->where('transaction_reference', 'like', '%' . $searchTerm . '%')
                    ->orWhere('transaction_hash', 'like', '%' . $searchTerm . '%');
            });
        }

        $withdrawals = $withdrawals_query->latest()->paginate(getSetting('pagination', 10));

        // Analytics query
        $stats = Withdrawal::where('user_id', $user_id)
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

        $withdrawals_analytics = [
            'total' => ['count' => (int) $stats->total_count, 'total' => $stats->total_sum],
            'pending' => ['count' => (int) $stats->pending_count, 'total' => $stats->pending_sum],
            'completed' => ['count' => (int) $stats->completed_count, 'total' => $stats->completed_sum],
            'failed' => ['count' => (int) $stats->failed_count, 'total' => $stats->failed_sum],
            'partial_payment' => ['count' => (int) $stats->partial_count, 'total' => $stats->partial_sum],
        ];

        // Advanced Analytics
        $avg_processing_time = Withdrawal::where('user_id', $user_id)
            ->where('status', 'completed')
            ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, updated_at)) as avg_seconds')
            ->value('avg_seconds');

        $fastest_withdrawal = Withdrawal::where('user_id', $user_id)
            ->where('status', 'completed')
            ->orderByRaw('TIMESTAMPDIFF(SECOND, created_at, updated_at) ASC')
            ->first();

        $slowest_withdrawal = Withdrawal::where('user_id', $user_id)
            ->where('status', 'completed')
            ->orderByRaw('TIMESTAMPDIFF(SECOND, created_at, updated_at) DESC')
            ->first();

        $fee_stats = Withdrawal::where('user_id', $user_id)
            ->selectRaw('SUM(fee_amount) as total_fees, AVG(fee_percent) as avg_fee_percent')
            ->first();

        $method_stats = Withdrawal::where('user_id', $user_id)
            ->selectRaw('withdrawal_method_id, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('withdrawal_method_id')
            ->with('withdrawalMethod')
            ->get();

        $currency_stats = Withdrawal::where('user_id', $user_id)
            ->selectRaw('currency, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('currency')
            ->get();

        $largest_withdrawal = Withdrawal::where('user_id', $user_id)
            ->orderByDesc('amount')
            ->first();

        $highest_daily_total = Withdrawal::where('user_id', $user_id)
            ->selectRaw('DATE(created_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->orderByDesc('total')
            ->first();

        return view("templates.$template.blades.user.withdrawals.index", compact(
            'page_title',
            'withdrawals',
            'withdrawals_analytics',
            'avg_processing_time',
            'fastest_withdrawal',
            'slowest_withdrawal',
            'fee_stats',
            'method_stats',
            'currency_stats',
            'largest_withdrawal',
            'highest_daily_total'
        ));
    }

    // view withdrawal
    public function viewWithdrawal(Request $request)
    {
        $template = config('site.template');
        $page_title = __('Withdrawal Details');
        $transaction_reference = $request->route('transaction_reference');
        $withdrawal = Withdrawal::where('transaction_reference', $transaction_reference)->where('user_id', auth()->id())->first();
        if (!$withdrawal) {
            return redirect()->route('user.withdrawals.index')->with('error', __('Withdrawal not found'));
        }
        return view("templates.$template.blades.user.withdrawals.view", compact(
            'page_title',
            'withdrawal'
        ));
    }


    // new withdrawal
    public function newWithdrawal()
    {
        $page_title = __('New Withdrawal');
        $template = config('site.template');
        $withdrawal_methods = WithdrawalMethod::active()->get();
        return view('templates.' . $template . '.blades.user.withdrawals.new', compact(
            'page_title',
            'withdrawal_methods'
        ));

    }

    // new withdrawal validate
    public function newWithdrawalValidate(Request $request)
    {
        $request->validate([
            'withdrawal_method_id' => 'required|exists:withdrawal_methods,id',
        ]);

        $withdrawal_method = WithdrawalMethod::active()->find($request->withdrawal_method_id);
        if (!$withdrawal_method) {
            return response()->json([
                'status' => 'error',
                'message' => __('Withdrawal method not found'),
            ], 404);
        }


        $min_withdrawal = getSetting('min_withdrawal');
        $max_withdrawal = getSetting('max_withdrawal');
        $withdrawal_fee = getSetting('withdrawal_fee');
        $website_currency = getSetting('currency');

        // make sure the amount is between the min and max
        $amount = $request->amount;
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

        $user = auth()->user();
        if ($amount > $user->balance) {
            return response()->json([
                'status' => 'error',
                'messsage' => __("Insufficient balance")
            ], 422);
        }

        $payment_information = json_decode($withdrawal_method->payment_information, true);

        $withdrawal_method_currency = $payment_information['currency'] ?? $website_currency;



        if ($withdrawal_method->class == 'manual') {

            $required_fields = $payment_information['fields'] ?? [];

            foreach ($required_fields as $field => $validation) {
                $request->validate([
                    $field => $validation,
                ]);
            }


            $structured_data = [];
            if ($withdrawal_method->type == 'crypto') {
                $structured_data = [
                    'transaction_hash' => null,
                    'wallet_address' => $request->wallet_address,
                    'currency' => $withdrawal_method_currency,
                    'network' => $payment_information['network'] ?? null,
                ];
            } elseif ($withdrawal_method->type == 'bank_transfer') {
                $structured_data = [
                    'bank_name' => $request->bank_name,
                    'account_holder' => $request->account_holder,
                    'account_number' => $request->account_number,
                    'routing_number' => $request->routing_number,
                    'swift' => $request->swift
                ];
            } elseif ($withdrawal_method->type == 'digital_wallet') {
                foreach ($required_fields as $field => $validation) {
                    $structured_data[$field] = $request->$field;
                }
            }

            $fee_percent = $withdrawal_fee;
            $fee_amount = $amount * ($fee_percent / 100);
            $amount_payable = $amount - $fee_amount;

            $conversion = rateConverter($amount_payable, $website_currency, $withdrawal_method_currency, 'withdrawal');
            $rate = $conversion['exchange_rate'];
            $converted_amount = $conversion['converted_amount'];
            $ref = \Str::orderedUuid();

            // debit user
            $user->refresh();
            $user->decrement('balance', $amount);

            $withdrawal = new Withdrawal();
            $withdrawal->user_id = auth()->id();
            $withdrawal->withdrawal_method_id = $withdrawal_method->id;
            $withdrawal->amount = $amount;
            $withdrawal->converted_amount = $converted_amount;
            $withdrawal->fee_percent = $fee_percent;
            $withdrawal->fee_amount = $fee_amount;
            $withdrawal->amount_payable = $amount_payable;
            $withdrawal->exchange_rate = $rate;
            $withdrawal->transaction_reference = $ref;
            $withdrawal->transaction_hash = null;
            $withdrawal->payment_proof = null;
            $withdrawal->currency = $withdrawal_method_currency;
            $withdrawal->structured_data = json_encode($structured_data);
            $withdrawal->auto_res_dump = null;
            $withdrawal->status = 'pending';
            $withdrawal->save();

            // record new transaction
            recordTransaction($user, $amount, $website_currency, $converted_amount, $withdrawal_method_currency, $rate, 'debit', 'completed', $ref, "Withdrawal request", $user->balance);

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

        // automatic withdrawal starts here
        $withdrawal_request = [
            'amount' => $request->amount,
            'withdrawal_method_id' => $withdrawal_method->id,
        ];

        $withdrawal_route_name = 'user.withdrawals.' . $withdrawal_method->pay;
        if (!Route::has($withdrawal_route_name)) {
            return response()->json([
                'status' => 'error',
                'message' => __('Withdrawal method not valid'),
            ], 422);
        }

        //encrypto $withdrawal_request and return url
        $encrypted = encrypt($withdrawal_request);
        $encrypted = urlencode($encrypted);

        return response()->json([
            'status' => 'success',
            'message' => __('Withdrawal request submitted successfully'),
            'redirect' => route($withdrawal_route_name, [
                'withdrawal_request' => $encrypted
            ])
        ]);
    }
}
