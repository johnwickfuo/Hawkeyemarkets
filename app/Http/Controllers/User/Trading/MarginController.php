<?php

namespace App\Http\Controllers\User\Trading;

use App\Http\Controllers\Controller;
use App\Models\MarginTradingOrder;
use App\Models\MarginTradingPosition;
use App\Services\LozandServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarginController extends Controller
{
    //check if the margin module is enabled
    public function __construct()
    {
        if (!moduleEnabled('margin_module')) {
            abort(404);
        }
    }
    public function index()
    {
        // get trading account
        $trading_account = auth()->user()->tradingAccounts()->where('account_type', 'margin')->first();
        if (!$trading_account) {
            return redirect()->route('user.trading.account')->with('error', __('You do not have a margin trading account.'));
        }

        $current_ticker = request()->route('ticker') ?? 'BTCUSDT';
        $page_title = __("Margin Trading");
        $template = config('site.template');
        $all_margin_tickers = [];
        $last_error_message = null;
        $current_ticker_info = [];
        $lozandServices = new LozandServices();
        $get_all_margin_tickers = $lozandServices->margins();
        if ($get_all_margin_tickers['status'] !== 'success') {
            $last_error_message = $get_all_margin_tickers['message'];
        } else {
            $all_margin_tickers = $get_all_margin_tickers['data'];
            //get the current ticker information from all cryptos
            foreach ($all_margin_tickers as $ticker) {
                if ($ticker['ticker'] == $current_ticker) {
                    $current_ticker_info = $ticker;
                    break;
                }
            }
        }

        //get recent trades
        $recent_trades = [];
        $get_recent_trades = $lozandServices->marginRecentTrades($current_ticker);
        if ($get_recent_trades['status'] !== 'success') {
            $last_error_message = $get_recent_trades['message'];
        } else {
            $recent_trades = $get_recent_trades['data'];
        }

        // order book
        $order_book = [];
        $get_order_book = $lozandServices->marginOrderBook($current_ticker);
        if ($get_order_book['status'] !== 'success') {
            $last_error_message = $get_order_book['message'];
        } else {
            $order_book = $get_order_book['data'];
        }

        $add_available = $trading_account->balance ?? 0.0;

        // Fetch user positions and orders
        $positions = auth()->user()->marginTradingPositions()->get();
        $open_orders = auth()->user()->marginTradingOrders()->where('status', 'pending')->get();
        $closed_orders = auth()->user()->marginTradingOrders()->where('status', '!=', 'pending')->latest()->take(20)->get();

        if (request()->ajax()) {
            return view("templates.{$template}.blades.user.trading.margin_inner", compact(
                'page_title',
                'all_margin_tickers',
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

        return view("templates.{$template}.blades.user.trading.margin", compact(
            'page_title',
            'all_margin_tickers',
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
            'ticker' => 'required',
            'amount' => 'required|numeric|min:0.00000001',
            'type' => 'required|in:limit,market',
            'side' => 'required|in:buy,sell',
            'leverage' => 'required|integer|min:1|max:100',
            'order_mode' => 'required|in:normal,borrow,repay',
            'price' => 'required_if:type,limit|numeric|min:0',
        ]);

        $user = auth()->user();
        $trading_account = $user->tradingAccounts()->where('account_type', 'margin')->first();

        if (!$trading_account) {
            return response()->json(['status' => 'error', 'message' => __('Trading account not found')], 404);
        }

        if ($trading_account->account_status !== 'active') {
            return response()->json(['status' => 'error', 'message' => __('Trading account is not active')], 403);
        }

        $ticker = $request->ticker;
        $type = $request->type;
        $leverage = (int) $request->leverage;
        $order_mode = $request->order_mode;

        // Handle Repay Mode (Manual Repayment)
        if ($order_mode === 'repay') {
            $repay_amount = (float) $request->amount;
            if ($trading_account->balance < $repay_amount) {
                return response()->json(['status' => 'error', 'message' => __('Insufficient balance for repayment')], 400);
            }
            if ($trading_account->borrowed <= 0) {
                return response()->json(['status' => 'error', 'message' => __('No debt to repay')], 400);
            }

            $actual_repay = min((float) $trading_account->borrowed, $repay_amount);

            DB::transaction(function () use ($trading_account, $actual_repay) {
                $trading_account->decrement('balance', (float) $actual_repay);
                $trading_account->decrement('borrowed', (float) $actual_repay);
            });

            return response()->json([
                'status' => 'success',
                'message' => __('Debt repaid successfully: ') . number_format($actual_repay, 2) . ' ' . $trading_account->currency
            ]);
        }

        // Get current price
        $lozandServices = new LozandServices();
        $ticker_info = $lozandServices->margin($ticker);
        if ($ticker_info['status'] !== 'success') {
            return response()->json(['status' => 'error', 'message' => __('Failed to fetch market data')], 400);
        }

        $current_price = (float) $ticker_info['data']['current_price'];
        $entry_price = $type === 'market' ? $current_price : (float) $request->price;

        if (!$entry_price || $entry_price <= 0) {
            return response()->json(['status' => 'error', 'message' => __('Invalid entry price')], 400);
        }

        //check side againts current price, tp and sl
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

        $locked_margin = 0;
        $quote_amount = (float) $request->amount;
        $base_amount = $quote_amount / $entry_price;

        // For margin trading, we always deduct margin based on size and leverage
        $locked_margin = ($base_amount * $entry_price) / $leverage;

        // Handle Auto-Borrow
        if ($order_mode === 'borrow' && $trading_account->balance < $locked_margin) {
            $borrow_amount = $locked_margin - (float) $trading_account->balance;
            $trading_account->increment('borrowed', (float) $borrow_amount);
            $trading_account->increment('balance', (float) $borrow_amount);
        }

        if ($trading_account->balance < $locked_margin) {
            return response()->json(['status' => 'error', 'message' => __('Insufficient balance for margin')], 400);
        }

        try {
            return DB::transaction(function () use ($user, $trading_account, $request, $entry_price, $current_price, $base_amount, $locked_margin, $leverage) {
                // Create Order
                $order = MarginTradingOrder::create([
                    'user_id' => $user->id,
                    'type' => $request->type,
                    'order_mode' => $request->order_mode,
                    'ticker' => $request->ticker,
                    'side' => $request->side,
                    'size' => $base_amount,
                    'price' => $entry_price,
                    'locked_margin' => $locked_margin,
                    'leverage' => $leverage,
                    'status' => $request->type === 'market' ? 'filled' : 'pending',
                    'timestamp' => (string) now()->valueOf(), // ms
                ]);

                if ($request->type === 'market') {
                    // Fill Market Order
                    $trading_account->decrement('balance', (float) $locked_margin);

                    // Check if there's an existing position to merge or reverse
                    $position = MarginTradingPosition::where('user_id', $user->id)
                        ->where('ticker', $request->ticker)
                        ->first();

                    if ($position) {
                        if ($position->side === $request->side) {
                            // ADDING to position
                            $total_size = $position->size + $base_amount;
                            $new_entry_price = (($position->entry_price * $position->size) + ($entry_price * $base_amount)) / $total_size;
                            $position->update([
                                'size' => $total_size,
                                'entry_price' => $new_entry_price,
                                'current_price' => $current_price,
                                'margin' => $position->margin + $locked_margin,
                                'timestamp' => (string) now()->valueOf(), // ms
                            ]);
                        } else {
                            // REDUCING or CLOSING position
                            if ($position->size > $base_amount) {
                                // Partial close: refund proportionate margin
                                $margin_to_refund = ((float) $position->margin / (float) $position->size) * $base_amount;
                                $trading_account->increment('balance', (float) $margin_to_refund);
                                $position->update([
                                    'size' => $position->size - $base_amount,
                                    'current_price' => $current_price,
                                    'margin' => $position->margin - $margin_to_refund,
                                    'timestamp' => (string) now()->valueOf(), // ms
                                ]);
                            } elseif ($position->size == $base_amount) {
                                // Full close: refund all margin
                                $trading_account->increment('balance', (float) $position->margin);
                                $position->delete();
                            } else {
                                // Reverse position: refund current and deduct new (already deducted above)
                                $trading_account->increment('balance', (float) $position->margin);
                                $remaining_base_amount = $base_amount - $position->size;
                                $position->update([
                                    'side' => $request->side,
                                    'size' => $remaining_base_amount,
                                    'entry_price' => $entry_price,
                                    'current_price' => $current_price,
                                    'margin' => $locked_margin,
                                    'timestamp' => (string) now()->valueOf(), // ms
                                ]);
                            }
                        }
                    } else {
                        // NEW position
                        MarginTradingPosition::create([
                            'user_id' => $user->id,
                            'ticker' => $request->ticker,
                            'side' => $request->side,
                            'size' => $base_amount,
                            'entry_price' => $entry_price,
                            'current_price' => $current_price,
                            'margin' => $locked_margin,
                            'leverage' => $leverage,
                            'timestamp' => (string) now()->valueOf(), // ms
                        ]);
                    }
                } else {
                    // LIMIT order: Lock margin
                    $trading_account->decrement('balance', (float) $locked_margin);
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

        $order = auth()->user()->marginTradingOrders()->findOrFail($request->order_id);

        if ($order->status !== 'pending') {
            return response()->json(['status' => 'error', 'message' => __('Only pending orders can be canceled')], 400);
        }

        try {
            return DB::transaction(function () use ($order) {
                // Refund locked margin
                if ($order->locked_margin > 0) {
                    $trading_account = auth()->user()->tradingAccounts()->where('account_type', 'margin')->first();
                    if ($trading_account) {
                        $refund_amount = (float) $order->locked_margin;
                        if ($trading_account->borrowed > 0) {
                            $to_repay = min((float) $trading_account->borrowed, $refund_amount);
                            $trading_account->decrement('borrowed', (float) $to_repay);
                            $refund_amount -= $to_repay;
                        }
                        if ($refund_amount > 0) {
                            $trading_account->increment('balance', (float) $refund_amount);
                        }
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
