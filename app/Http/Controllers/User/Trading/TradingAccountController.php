<?php

namespace App\Http\Controllers\User\Trading;

use App\Http\Controllers\Controller;
use App\Models\ForexTradingOrder;
use App\Models\FuturesTradingOrders;
use App\Models\MarginTradingOrder;
use App\Models\TradingAccount;
use Illuminate\Http\Request;

class TradingAccountController extends Controller
{
    //check if all 3 forex, futures, margin are enabled
    public function __construct()
    {
        if (!moduleEnabled('forex_module') && !moduleEnabled('futures_module') && !moduleEnabled('margin_module')) {
            abort(404);
        }
    }
    public function index()
    {
        $page_title = __('Trading Account');
        $template = config('site.template');
        $user = auth()->user();
        $trading_accounts = [
            'futures' => $user->tradingAccounts()->where('account_type', 'futures')->first(),
            'forex_live' => $user->tradingAccounts()->where('account_type', 'forex')->where('mode', 'live')->first(),
            'forex_demo' => $user->tradingAccounts()->where('account_type', 'forex')->where('mode', 'demo')->first(),
            'margin' => $user->tradingAccounts()->where('account_type', 'margin')->first(),
        ];

        $has_trading_account = $user->tradingAccounts()
            ->where(function ($query) {
                $query->where('account_type', '!=', 'forex')
                    ->orWhere('mode', '!=', 'demo');
            })
            ->exists();

        // calcutate networth
        $fiat_balance = $user->balance;
        $futures_balance = $trading_accounts['futures']->balance ?? 0;
        $margin_balance = $trading_accounts['margin']->balance ?? 0;
        $forex_live_balance = $trading_accounts['forex_live']->balance ?? 0;
        // convert all
        $futures_balance_conversion = rateConverter($futures_balance, "USDT", getSetting('currency'), 'futures');
        $futures_balance_converted = $futures_balance_conversion['converted_amount'];
        $margin_balance_conversion = rateConverter($margin_balance, "USDT", getSetting('currency'), 'margin');
        $margin_balance_converted = $margin_balance_conversion['converted_amount'];
        $forex_live_balance_conversion = rateConverter($forex_live_balance, "USD", getSetting('currency'), 'forex');
        $forex_live_balance_converted = $forex_live_balance_conversion['converted_amount'];
        $total_networth = $fiat_balance;
        if (moduleEnabled('futures_module')) {
            $total_networth += $futures_balance_converted;
        }
        if (moduleEnabled('margin_module')) {
            $total_networth += $margin_balance_converted;
        }
        if (moduleEnabled('forex_module')) {
            $total_networth += $forex_live_balance_converted;
        }





        // Fetch latest orders
        $futuresOrders = FuturesTradingOrders::where('user_id', $user->id)->latest()->take(10)->get();
        $marginOrders = MarginTradingOrder::where('user_id', $user->id)->latest()->take(10)->get();
        $forexLiveOrders = ForexTradingOrder::where('user_id', $user->id)->where('mode', 'live')->latest()->take(10)->get();
        $forexDemoOrders = ForexTradingOrder::where('user_id', $user->id)->where('mode', 'demo')->latest()->take(10)->get();

        return view("templates.{$template}.blades.user.trading.account", compact(
            'page_title',
            'trading_accounts',
            'user',
            'has_trading_account',
            'futuresOrders',
            'marginOrders',
            'forexLiveOrders',
            'forexDemoOrders',
            'total_networth'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_type' => 'required|in:futures,margin,forex',
        ]);


        $user = auth()->user();
        $currencys = [
            'futures' => 'USDT',
            'margin' => 'USDT',
            'forex' => 'USD',
        ];

        if ($request->account_type === 'forex' && !moduleEnabled('forex_module')) {
            abort(404);
        }
        if ($request->account_type === 'futures' && !moduleEnabled('futures_module')) {
            abort(404);
        }
        if ($request->account_type === 'margin' && !moduleEnabled('margin_module')) {
            abort(404);
        }

        $currency = $currencys[$request->account_type];
        // Check if account already exists
        $query = $user->tradingAccounts()->where('account_type', $request->account_type);
        if ($request->account_type === 'forex') {
            $query->where('mode', $request->mode);
        }
        $exists = $query->exists();

        if ($exists) {
            $typeLabel = ucfirst($request->account_type);
            if ($request->account_type === 'forex') {
                $typeLabel .= ' (' . ucfirst($request->mode) . ')';
                $request->validate([
                    'mode' => 'in:live,demo',
                ]);
            }
            $errorMessage = __('You already have a :type trading account.', ['type' => $typeLabel]);
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $errorMessage], 422);
            }
            return back()->with('error', $errorMessage);
        }

        $data = [
            'user_id' => $user->id,
            'account_type' => $request->account_type,
            'account_status' => 'active',
            'balance' => ($request->account_type === 'forex' && $request->mode === 'demo') ? 100000.0 : 0.0,
            'currency' => $currency,
        ];

        if ($request->account_type === 'forex') {
            $data['mode'] = $request->mode;
            $data['equity'] = ($request->mode === 'demo') ? 100000.0 : 0.0;
        }

        TradingAccount::create($data);

        $successMessage = __('Trading account created successfully.');
        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => $successMessage]);
        }

        return back()->with('success', $successMessage);
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'from_type' => 'required|in:fiat,futures,margin,forex_live',
            'to_type' => 'required|in:fiat,futures,margin,forex_live,forex_demo|different:from_type',
            'amount' => 'required|numeric|min:0.01',
        ]);


        if ((str_replace(['_live', '_demo'], '', $request->from_type) === 'forex' || str_replace(['_live', '_demo'], '', $request->to_type) === 'forex') && !moduleEnabled('forex_module')) {
            abort(404);
        }
        if (($request->from_type === 'futures' || $request->to_type === 'futures') && !moduleEnabled('futures_module')) {
            abort(404);
        }
        if (($request->from_type === 'margin' || $request->to_type === 'margin') && !moduleEnabled('margin_module')) {
            abort(404);
        }

        $user = auth()->user();
        $amount = $request->amount;

        // Get source account balance
        $fromBalance = 0;
        $fromAccount = null;

        if ($request->from_type === 'fiat') {
            $fromBalance = $user->balance;
        } else {
            $type = $request->from_type;
            $mode = null;
            if (str_contains($type, 'forex_')) {
                $mode = str_replace('forex_', '', $type);
                $type = 'forex';
            }
            $fromAccount = $user->tradingAccounts()->where('account_type', $type);
            if ($mode)
                $fromAccount->where('mode', $mode);
            $fromAccount = $fromAccount->first();

            if (!$fromAccount) {
                return response()->json(['status' => 'error', 'message' => __('Source account not initialized.')], 422);
            }
            $fromBalance = $fromAccount->balance;
        }

        if ($fromBalance < $amount) {
            return response()->json(['status' => 'error', 'message' => __('Insufficient funds in source account.')], 422);
        }

        // Get destination account
        $toAccount = null;
        if ($request->to_type !== 'fiat') {
            $type = $request->to_type;
            $mode = null;
            if (str_contains($type, 'forex_')) {
                $mode = str_replace('forex_', '', $type);
                $type = 'forex';
            }
            $toAccount = $user->tradingAccounts()->where('account_type', $type);
            if ($mode)
                $toAccount->where('mode', $mode);
            $toAccount = $toAccount->first();

            if (!$toAccount) {
                return response()->json(['status' => 'error', 'message' => __('Destination account not initialized.')], 422);
            }
        }

        // perform conversion
        $from_currency = $fromAccount->currency ?? getSetting('currency');
        $to_currency = $toAccount->currency ?? getSetting('currency');

        $conversion = rateConverter($amount, $from_currency, $to_currency, 'transfer');
        $converted_amount = $conversion['converted_amount'];
        $fiat_amount = $from_currency === getSetting('currency') ? $amount : $converted_amount;
        $fiat_currency = $from_currency === getSetting('currency') ? $from_currency : $to_currency;
        $converted_currency = $conversion['to_currency'];
        $rate = $conversion['exchange_rate'];
        $credit_or_debit = $request->from_type === 'fiat' ? 'debit' : 'credit';

        //make sure the account status is active if the from account is not fiat
        if ($request->from_type !== 'fiat') {
            if ($fromAccount->account_status !== 'active') {
                return response()->json(['status' => 'error', 'message' => __('Source account is not active.')], 422);
            }
        }


        // Perform Transfer
        \DB::transaction(function () use ($user, $fromAccount, $toAccount, $amount, $request, $converted_amount, $fiat_amount, $fiat_currency, $credit_or_debit, $converted_currency, $rate) {
            // Deduct
            if ($request->from_type === 'fiat') {
                $user->decrement('balance', $converted_amount);
            } else {
                $fromAccount->decrement('balance', $converted_amount);
            }

            // Add
            if ($request->to_type === 'fiat') {
                $user->increment('balance', $converted_amount);
            } else {
                $toAccount->increment('balance', $converted_amount);
            }

            //record transaction
            $user->refresh();
            $ref = \Str::orderedUuid();
            recordTransaction($user, $fiat_amount, $fiat_currency, $converted_amount, $converted_currency, $rate, $credit_or_debit, 'completed', $ref, "Trading Account Transfer", $user->balance);

            // Record New Notification Message
            $title = __('Trading Account Transfer');
            $body = __('Your trading account transfer of :amount :currency from :from to :to has been completed successfully.', ['from' => $request->from_type, 'to' => $request->to_type, 'amount' => $fiat_amount, 'currency' => $fiat_currency]);
            recordNotificationMessage($user, $title, $body);
        });

        return response()->json(['status' => 'success', 'message' => __('Transfer successful.')]);
    }
}
