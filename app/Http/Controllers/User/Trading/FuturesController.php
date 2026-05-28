<?php

namespace App\Http\Controllers\User\Trading;

use App\Http\Controllers\Controller;
use App\Models\FuturesTradingOrders;
use App\Models\FuturesTradingPositions;
use App\Services\LozandServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuturesController extends Controller
{
    // check if futures module is enabled
    public function __construct()
    {
        if (!moduleEnabled('futures_module')) {
            abort(404);
        }
    }
    public function index()
    {
        // get trading account
        $trading_account = auth()->user()->tradingAccounts()->where('account_type', 'futures')->first();
        if (!$trading_account) {
            return redirect()->route('user.trading.account')->with('error', __('You do not have a futures trading account.'));
        }
        $current_ticker = request()->route('ticker') ?? 'BTCUSDT';
        $page_title = __("Futures Trading");
        $template = config('site.template');
        $all_crypto_tickers = [];
        $last_error_message = null;
        $current_ticker_info = [];
        $lozandServices = new LozandServices();
        $get_all_crypto_tickers = $lozandServices->futureTickers();
        if ($get_all_crypto_tickers['status'] !== 'success') {
            $last_error_message = $get_all_crypto_tickers['message'];
        } else {
            $all_crypto_tickers = $get_all_crypto_tickers['data'];
            //get the current ticker information from all cryptos
            foreach ($all_crypto_tickers as $ticker) {
                if ($ticker['ticker'] == $current_ticker) {
                    $current_ticker_info = $ticker;
                    break;
                }
            }
        }

        //get recent trades
        $recent_trades = [];
        $get_recent_trades = $lozandServices->futuresRecentTrades($current_ticker);
        if ($get_recent_trades['status'] !== 'success') {
            $last_error_message = $get_recent_trades['message'];
        } else {
            $recent_trades = $get_recent_trades['data'];
        }

        // order book
        $order_book = [];
        $get_order_book = $lozandServices->futuresOrderBook($current_ticker);
        if ($get_order_book['status'] !== 'success') {
            $last_error_message = $get_order_book['message'];
        } else {
            $order_book = $get_order_book['data'];
        }

        $add_available = $trading_account->balance ?? 0.0;

        // Fetch user positions and orders
        $positions = auth()->user()->futuresTradingPositions()->get();
        $open_orders = auth()->user()->futuresTradingOrders()->where('status', 'pending')->get();
        $closed_orders = auth()->user()->futuresTradingOrders()->where('status', '!=', 'pending')->latest()->take(20)->get();

        if (request()->ajax()) {
            return view("templates.{$template}.blades.user.trading.futures_inner", compact(
                'page_title',
                'all_crypto_tickers',
                'last_error_message',
                'current_ticker_info',
                'current_ticker',
                'recent_trades',
                'order_book',
                'add_available',
                'trading_account',
                'positions',
                'open_orders',
                'closed_orders'
            ));
        }

        return view("templates.{$template}.blades.user.trading.futures", compact(
            'page_title',
            'all_crypto_tickers',
            'last_error_message',
            'current_ticker_info',
            'current_ticker',
            'recent_trades',
            'order_book',
            'add_available',
            'trading_account',
            'positions',
            'open_orders',
            'closed_orders'
        ));
    }

    public function trade(Request $request)
    {
        $request->validate([
            'ticker' => 'required|string',
            'type' => 'required|in:limit,market',
            'side' => 'required|in:buy,sell',
            'amount' => 'required|numeric|min:0.000001',
            'price' => 'nullable|numeric|min:0',
            'leverage' => 'required|integer|min:1|max:50',
            'take_profit' => 'required|numeric|min:0',
            'stop_loss' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();
        $trading_account = $user->tradingAccounts()->where('account_type', 'futures')->first();

        if (!$trading_account) {
            return response()->json(['status' => 'error', 'message' => __('Trading account not found')], 404);
        }

        if ($trading_account->account_status !== 'active') {
            return response()->json(['status' => 'error', 'message' => __('Trading account is not active')], 403);
        }

        $ticker = $request->ticker;
        $type = $request->type;
        $leverage = (int) $request->leverage;

        // Get current price
        $lozandServices = new LozandServices();
        $ticker_info = $lozandServices->futureTicker($ticker);
        if ($ticker_info['status'] !== 'success') {
            return response()->json(['status' => 'error', 'message' => __('Failed to fetch market data')], 400);
        }

        $current_price = (float) $ticker_info['data']['current_price'];
        $entry_price = $type === 'market' ? $current_price : (float) $request->price;

        if (!$entry_price || $entry_price <= 0) {
            return response()->json(['status' => 'error', 'message' => __('Invalid entry price')], 400);
        }

        // check $side against $take_profit and $stop_loss
        if ($request->side === 'buy' && $request->take_profit < $entry_price) {
            return response()->json(['status' => 'error', 'message' => __('Take profit should be greater than entry price')], 400);
        }

        if ($request->side === 'sell' && $request->take_profit > $entry_price) {
            return response()->json(['status' => 'error', 'message' => __('Take profit should be less than entry price')], 400);
        }

        if ($request->side === 'buy' && $request->stop_loss > $entry_price) {
            return response()->json(['status' => 'error', 'message' => __('Stop loss should be less than entry price')], 400);
        }

        if ($request->side === 'sell' && $request->stop_loss < $entry_price) {
            return response()->json(['status' => 'error', 'message' => __('Stop loss should be greater than entry price')], 400);
        }

        // Check if this is a closing order
        $position = FuturesTradingPositions::where('user_id', $user->id)
            ->where('ticker', $request->ticker)
            ->first();

        // Calculate required margin for the "opening" part of the order (the excess)
        $locked_margin = 0;
        $quote_amount = (float) $request->amount;
        $base_amount = $quote_amount / $entry_price;

        if ($position && $position->side !== $request->side) {
            if ($base_amount > $position->size) {
                // Reverse: Excess size requires new margin
                $excess_base_amount = $base_amount - $position->size;
                $locked_margin = ($excess_base_amount * $entry_price) / $leverage;
            }
        } else {
            // Opening: Full size requires margin
            $locked_margin = ($base_amount * $entry_price) / $leverage;
        }

        if ($locked_margin > 0 && $trading_account->balance < $locked_margin) {
            return response()->json(['status' => 'error', 'message' => __('Insufficient balance for margin')], 400);
        }

        try {
            return DB::transaction(function () use ($user, $trading_account, $request, $entry_price, $current_price, $base_amount, $position, $locked_margin, $leverage) {
                // Create Order
                $order = FuturesTradingOrders::create([
                    'user_id' => $user->id,
                    'type' => $request->type,
                    'ticker' => $request->ticker,
                    'side' => $request->side,
                    'size' => $base_amount,
                    'price' => $entry_price,
                    'take_profit' => $request->take_profit,
                    'stop_loss' => $request->stop_loss,
                    'locked_margin' => $locked_margin,
                    'leverage' => $leverage,
                    'status' => $request->type === 'market' ? 'filled' : 'pending',
                    'order_id' => 'ORD-' . strtoupper(\Str::random(10)),
                    'timestamp' => (string) now()->valueOf(), // ms
                ]);

                if ($request->type === 'market') {
                    // Fill Market Order
                    if ($position) {
                        if ($position->side === $request->side) {
                            // ADDING to position
                            if ($locked_margin > 0) {
                                $trading_account->decrement('balance', $locked_margin);
                            }
                            $total_size = $position->size + $base_amount;
                            $new_entry_price = (($position->entry_price * $position->size) + ($entry_price * $base_amount)) / $total_size;
                            $position->update([
                                'size' => $total_size,
                                'entry_price' => $new_entry_price,
                                'current_price' => $current_price,
                                'margin' => $position->margin + $locked_margin,
                                'take_profit' => $request->take_profit,
                                'stop_loss' => $request->stop_loss,
                                'unrealized_pnl' => 0,
                                'realized_pnl' => 0,
                                'timestamp' => (string) now()->valueOf(), // ms
                            ]);
                        } else {
                            // REDUCING or CLOSING position
                            if ($position->size > $base_amount) {
                                // Partial close: refund proportionate margin
                                $margin_to_refund = ($position->margin / $position->size) * $base_amount;
                                $trading_account->increment('balance', $margin_to_refund);
                                $position->update([
                                    'size' => $position->size - $base_amount,
                                    'current_price' => $current_price,
                                    'margin' => $position->margin - $margin_to_refund,
                                    'timestamp' => (string) now()->valueOf(), // ms
                                ]);
                            } elseif ($position->size == $base_amount) {
                                // Full close: refund all margin
                                $trading_account->increment('balance', $position->margin);
                                $position->delete();
                            } else {
                                // Reverse position: refund current and deduct new
                                $trading_account->increment('balance', $position->margin);
                                if ($locked_margin > 0) {
                                    $trading_account->decrement('balance', $locked_margin);
                                }
                                $remaining_base_amount = $base_amount - $position->size;
                                $position->update([
                                    'side' => $request->side,
                                    'size' => $remaining_base_amount,
                                    'entry_price' => $entry_price,
                                    'current_price' => $current_price,
                                    'margin' => $locked_margin,
                                    'take_profit' => $request->take_profit,
                                    'stop_loss' => $request->stop_loss,
                                    'unrealized_pnl' => 0,
                                    'realized_pnl' => 0,
                                    'timestamp' => (string) now()->valueOf(), // ms
                                ]);
                            }
                        }
                    } else {
                        // NEW position
                        if ($locked_margin > 0) {
                            $trading_account->decrement('balance', $locked_margin);
                        }
                        FuturesTradingPositions::create([
                            'user_id' => $user->id,
                            'ticker' => $request->ticker,
                            'side' => $request->side,
                            'size' => $base_amount,
                            'entry_price' => $entry_price,
                            'current_price' => $current_price,
                            'margin' => $locked_margin,
                            'leverage' => $request->leverage,
                            'take_profit' => $request->take_profit,
                            'stop_loss' => $request->stop_loss,
                            'unrealized_pnl' => 0,
                            'realized_pnl' => 0,
                            'timestamp' => (string) now()->valueOf(), // ms
                        ]);
                    }
                } else {
                    // LIMIT order: Lock margin
                    if ($locked_margin > 0) {
                        $trading_account->decrement('balance', $locked_margin);
                    }
                }

                return response()->json([
                    'status' => 'success',
                    'message' => $request->type === 'market' ? __('Order filled successfully') : __('Order placed successfully'),
                    'order' => $order
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function cancelOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required'
        ]);

        $order = auth()->user()->futuresTradingOrders()->findOrFail($request->order_id);

        if ($order->status !== 'pending') {
            return response()->json(['status' => 'error', 'message' => __('Only pending orders can be canceled')], 400);
        }

        try {
            return DB::transaction(function () use ($order) {
                // Refund locked margin
                if ($order->locked_margin > 0) {
                    $trading_account = auth()->user()->tradingAccounts()->where('account_type', 'futures')->first();
                    if ($trading_account) {
                        $trading_account->increment('balance', $order->locked_margin);
                    }
                }

                $order->update(['status' => 'canceled']);

                return response()->json([
                    'status' => 'success',
                    'message' => __('Order canceled successfully')
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
