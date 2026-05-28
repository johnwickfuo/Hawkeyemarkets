<?php

namespace App\Http\Controllers\User\Trading;

use App\Http\Controllers\Controller;
use App\Services\LozandServices;
use Illuminate\Http\Request;
use App\Models\ForexTradingOrder;
use App\Models\ForexTradingPosition;
use Illuminate\Support\Facades\DB;

class ForexController extends Controller
{
    // check if forex module is enabled
    public function __construct()
    {
        if (!moduleEnabled('forex_module')) {
            abort(404);
        }
    }
    public function index()
    {
        $page_title = __("Forex Trading");
        $template = config('site.template');
        $current_ticker = request()->route('ticker') ?? 'EUR_USD';
        $route_name = request()->route()->getName();
        $mode = explode('.', $route_name)[3];
        $current_ticker_formatted = str_replace('_', '/', $current_ticker);
        $lozand = new LozandServices();
        $forex_tickers = [];
        $current_ticker_info = [];
        $last_error_message = null;
        $forex_tickers_request = $lozand->forexTickers();
        if ($forex_tickers_request['status'] == 'success') {
            $forex_tickers = $forex_tickers_request['data'];
            $current_ticker_info = collect($forex_tickers)->firstWhere('s', $current_ticker_formatted);
        } else {
            $last_error_message = $forex_tickers_request['message'];
        }

        //get trading account
        $trading_account = auth()->user()->tradingAccounts()
            ->where('account_type', 'forex')
            ->where('account_status', 'active')
            ->where('mode', $mode)
            ->first();

        if (!$trading_account) {
            return redirect()->route('user.trading.account')->with('error', 'Trading account not found');
        }

        // Calculate stats
        $positions = auth()->user()->forexTradingPositions()
            ->where('status', 'open')
            ->where('mode', $mode)
            ->get();
        $used_margin = (float) $positions->sum('margin');
        $unrealized_pnl = (float) $positions->sum('unrealized_pnl');

        $balance = (float) $trading_account->balance;
        $equity = $balance + $unrealized_pnl;

        $pendingOrders = auth()->user()->forexTradingOrders()
            ->where('status', 'pending')
            ->where('mode', $mode)
            ->get();
        $history = auth()->user()->forexTradingOrders()
            ->whereIn('status', ['filled', 'canceled'])
            ->where('mode', $mode)
            ->get();

        $margin_level = $used_margin > 0 ? ($equity / $used_margin) * 100 : 0;

        // Update model values for view consistency
        $trading_account->equity = $equity;
        $trading_account->margin_level = $margin_level;




        if (request()->ajax()) {
            return view("templates.{$template}.blades.user.trading.forex_inner", compact(
                'page_title',
                'current_ticker',
                'current_ticker_formatted',
                'forex_tickers',
                'current_ticker_info',
                'last_error_message',
                'trading_account',
                'mode',
                'positions',
                'pendingOrders',
                'history'
            ));
        }

        return view("templates.{$template}.blades.user.trading.forex", compact(
            'page_title',
            'current_ticker',
            'current_ticker_formatted',
            'forex_tickers',
            'current_ticker_info',
            'last_error_message',
            'trading_account',
            'mode',
            'positions',
            'pendingOrders',
            'history'
        ));
    }

    public function trade(Request $request)
    {
        $request->validate([
            'symbol' => 'required',
            'type' => 'required|in:Buy,Sell',
            'volume' => 'required|numeric|min:0.01',
            'order_type' => 'required|in:Market,Limit,Stop',
            'price' => 'nullable|numeric',
            'stop_loss' => 'nullable|numeric',
            'take_profit' => 'nullable|numeric',
            'mode' => 'required|in:live,demo',
        ]);

        $user = auth()->user();
        $trading_account = $user->tradingAccounts()
            ->where('account_type', 'forex')
            ->where('mode', $request->mode)
            ->first();

        if (!$trading_account) {
            return response()->json(['status' => 'error', 'message' => 'Trading account not found']);
        }

        // Logic for margin calculation (simplified)
        // 1 Lot = 100,000 units. Margin = (Units * Price) / Leverage
        // Leverage is assumed to be 100 for now.
        $leverage = 100;
        $units = $request->volume * 100000;
        $price = $request->price ?? 0; // Market price logic would happen here if Market order

        // For simplicity in this implementation, we take price from request or ticker
        $lozand = new LozandServices();
        $ticker_request = $lozand->forexTickers();
        if ($ticker_request['status'] == 'success') {
            $ticker = collect($ticker_request['data'])->firstWhere('s', $request->symbol);
            $price = ($request->type == 'Buy') ? $ticker['a'] : $ticker['b'];
        }


        // check tp and sl against $price
        if ($request->type == 'Buy' && $request->take_profit && $request->take_profit < $price) {
            return response()->json(['status' => 'error', 'message' => 'Take profit should be greater than entry price']);
        }

        if ($request->type == 'Sell' && $request->take_profit && $request->take_profit > $price) {
            return response()->json(['status' => 'error', 'message' => 'Take profit should be less than entry price']);
        }

        if ($request->type == 'Buy' && $request->stop_loss && $request->stop_loss > $price) {
            return response()->json(['status' => 'error', 'message' => 'Stop loss should be less than entry price']);
        }

        if ($request->type == 'Sell' && $request->stop_loss && $request->stop_loss < $price) {
            return response()->json(['status' => 'error', 'message' => 'Stop loss should be greater than entry price']);
        }

        $margin_required = ($units * $price) / $leverage;

        if ($trading_account->balance < $margin_required) {
            return response()->json(['status' => 'error', 'message' => 'Insufficient balance for this trade']);
        }

        DB::beginTransaction();
        try {
            $order = ForexTradingOrder::create([
                'user_id' => $user->id,
                'symbol' => $request->symbol,
                'mode' => $request->mode,
                'type' => $request->type,
                'order_type' => $request->order_type,
                'volume' => $request->volume,
                'price' => $price,
                'stop_loss' => $request->stop_loss,
                'take_profit' => $request->take_profit,
                'status' => $request->order_type == 'Market' ? 'filled' : 'pending',
            ]);

            if ($request->order_type == 'Market') {
                $trading_account->decrement('balance', $margin_required);

                ForexTradingPosition::create([
                    'user_id' => $user->id,
                    'symbol' => $request->symbol,
                    'mode' => $request->mode,
                    'side' => $request->type,
                    'volume' => $request->volume,
                    'entry_price' => $price,
                    'current_price' => $price,
                    'stop_loss' => $request->stop_loss,
                    'take_profit' => $request->take_profit,
                    'margin' => $margin_required,
                    'status' => 'open',
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Order placed successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function cancelOrder($id)
    {
        $order = auth()->user()->forexTradingOrders()->findOrFail($id);
        if ($order->status != 'pending') {
            return response()->json(['status' => 'error', 'message' => 'Only pending orders can be canceled']);
        }

        $order->update(['status' => 'canceled']);
        return response()->json(['status' => 'success', 'message' => 'Order canceled successfully']);
    }

    public function closePosition($id)
    {
        $position = auth()->user()->forexTradingPositions()->where('status', 'open')->findOrFail($id);
        $user = auth()->user();

        $lozand = new LozandServices();
        $ticker_request = $lozand->forexTickers();
        $price = $position->current_price;

        if ($ticker_request['status'] == 'success') {
            $ticker = collect($ticker_request['data'])->firstWhere('s', str_replace('_', '/', $position->symbol));
            $price = ($position->side == 'Buy') ? $ticker['b'] : $ticker['a'];
        }

        $trading_account = $user->tradingAccounts()
            ->where('account_type', 'forex')
            ->where('account_status', 'active')
            ->where('mode', $position->mode)
            ->first();

        // Re-matching mode for accuracy
        $positions_count = $user->forexTradingPositions()->where('status', 'open')->count();
        // This logic might need refinement if user has both live/demo open positions.
        // But usually only one account is "active" in the session.

        DB::beginTransaction();
        try {
            // Calculate final PnL
            $units = $position->volume * 100000;
            $pnl = ($position->side === 'Buy')
                ? ($price - $position->entry_price) * $units
                : ($position->entry_price - $price) * $units;

            // Refund Margin + PnL
            $final_amount = $position->margin + $pnl;
            $trading_account->increment('balance', $final_amount);

            // Create closing order
            ForexTradingOrder::create([
                'user_id' => $user->id,
                'symbol' => $position->symbol,
                'mode' => $position->mode,
                'type' => $position->side === 'Buy' ? 'Sell' : 'Buy',
                'order_type' => 'Market',
                'volume' => $position->volume,
                'price' => $price,
                'status' => 'filled',
            ]);

            $position->update(['status' => 'closed', 'current_price' => $price, 'unrealized_pnl' => $pnl]);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Position closed successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
