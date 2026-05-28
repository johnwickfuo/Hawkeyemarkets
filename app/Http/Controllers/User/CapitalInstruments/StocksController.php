<?php

namespace App\Http\Controllers\User\CapitalInstruments;

use App\Http\Controllers\Controller;
use App\Models\StockHolding;
use App\Models\StockHoldingHistory;
use App\Services\LozandServices;
use Illuminate\Http\Request;

class StocksController extends Controller
{
    public function __construct()
    {
        if (!moduleEnabled('stock_module')) {
            abort(404);
        }
    }
    public function index()
    {
        $page_title = __("Stocks");
        $template = config('site.template');

        $lozand = new LozandServices();
        $stocks = $lozand->marketStocks();
        $marketStocks = [];

        if ($stocks['status'] == 'success') {
            $marketStocks = $stocks['data'];
        }

        $message = $stocks['message'] ?? null;

        // $holdings = StockHolding::where('user_id', auth()->user()->id)->get();
        $holding_histories = StockHoldingHistory::where('user_id', auth()->user()->id)->paginate(getSetting('pagination'));

        // analytics
        $userId = auth()->id();
        $dec = 18;
        $scale = (int) getSetting('decimal_places');

        // Holdings collection (we need cost basis calc)
        $holdings = StockHolding::where('user_id', $userId)->orderBy('id', 'desc')->get();

        $positions_count = $holdings->count();
        $total_shares = (float) $holdings->sum('shares');

        $total_cost_basis = (float) $holdings->sum(fn($h) => (float) $h->shares * (float) $h->average_price);
        $total_pnl = (float) $holdings->sum('pnl');

        $total_pnl_percent = $total_cost_basis > 0
            ? round(($total_pnl / $total_cost_basis) * 100, 2)
            : 0;

        $top_gainer = $holdings->sortByDesc('pnl')->first();
        $top_loser = $holdings->sortBy('pnl')->first();

        $largest = $holdings->sortByDesc(fn($h) => (float) $h->shares * (float) $h->average_price)->first();
        $largest_cost = $largest ? (float) $largest->shares * (float) $largest->average_price : 0;
        $largest_pct = $total_cost_basis > 0 ? round(($largest_cost / $total_cost_basis) * 100, 2) : 0;

        // Histories aggregates
        $histories = StockHoldingHistory::where('user_id', $userId);

        $total_trades = (clone $histories)->count();
        $buys_count = (clone $histories)->bought()->count();
        $sells_count = (clone $histories)->sold()->count();

        $buy_volume_usd = (float) (clone $histories)->bought()
            ->selectRaw("COALESCE(SUM(CAST(amount_usd AS DECIMAL($dec,$scale))),0) as total")->value('total');

        $sell_volume_usd = (float) (clone $histories)->sold()
            ->selectRaw("COALESCE(SUM(CAST(amount_usd AS DECIMAL($dec,$scale))),0) as total")->value('total');

        $total_fees_paid = (float) (clone $histories)
            ->selectRaw("COALESCE(SUM(CAST(fee_amount AS DECIMAL($dec,$scale))),0) as total")->value('total');

        $most_traded = (clone $histories)
            ->select('ticker')
            ->selectRaw("COUNT(*) as trades")
            ->groupBy('ticker')
            ->orderByDesc('trades')
            ->first();

        $last_trade_at = (clone $histories)->max('created_at');

        $stock_analytics = [
            'positions_count' => $positions_count,
            'total_shares' => round($total_shares, $scale),
            'total_cost_basis' => round($total_cost_basis, $scale),
            'total_pnl' => round($total_pnl, $scale),
            'total_pnl_percent' => $total_pnl_percent,

            'top_gainer' => $top_gainer ? [
                'ticker' => $top_gainer->ticker,
                'pnl' => (float) $top_gainer->pnl,
                'pnl_percent' => (float) $top_gainer->pnl_percent,
            ] : null,

            'top_loser' => $top_loser ? [
                'ticker' => $top_loser->ticker,
                'pnl' => (float) $top_loser->pnl,
                'pnl_percent' => (float) $top_loser->pnl_percent,
            ] : null,

            'largest_position' => $largest ? [
                'ticker' => $largest->ticker,
                'cost_basis' => round($largest_cost, $scale),
                'percent_of_portfolio' => $largest_pct,
            ] : null,

            'total_trades' => $total_trades,
            'buys_count' => $buys_count,
            'sells_count' => $sells_count,
            'buy_volume_usd' => round($buy_volume_usd, $scale),
            'sell_volume_usd' => round($sell_volume_usd, $scale),
            'total_fees_paid' => round($total_fees_paid, $scale),

            'most_traded_ticker' => $most_traded ? [
                'ticker' => $most_traded->ticker,
                'trades' => (int) $most_traded->trades,
            ] : null,

            'last_trade_at' => $last_trade_at,
        ];


        return view("templates.{$template}.blades.user.capital-instruments.stocks.index", compact('page_title', 'marketStocks', 'holdings', 'holding_histories', 'message', 'stock_analytics'));
    }


    // return view of buy stock
    public function buyStock($ticker)
    {
        $page_title = __("Buy Stock");
        $template = config('site.template');

        $lozand = new LozandServices();
        $stock = $lozand->ticker($ticker);
        $marketStock = [];

        if ($stock['status'] == 'success') {
            $marketStock = $stock['data'];
        }

        $message = $stock['message'] ?? null;



        return view("templates.{$template}.blades.user.capital-instruments.stocks.buy", compact('page_title', 'marketStock', 'message'));
    }

    public function buyStockValidate(Request $request, $ticker)
    {
        // stocks
        $min_stock_purchase = getSetting('min_stock_purchase');
        $max_stock_purchase = getSetting('max_stock_purchase');
        $stock_purchase_fee_percent = getSetting('stock_purchase_fee_percent');

        $request->validate([
            'amount' => 'required|numeric',
            'ticker' => 'required|string',
        ]);

        $amount = $request->amount;
        $user = auth()->user();


        // check for min and max amount
        if ($amount < $min_stock_purchase || $amount > $max_stock_purchase) {
            $error_message = __('Amount must be between :min and :max', ['min' => $min_stock_purchase, 'max' => $max_stock_purchase]);
            return response()->json([
                'status' => 'error',
                'message' => $error_message
            ], 422);
        }

        // check for balance
        $fee_amount = $amount * ($stock_purchase_fee_percent / 100);
        $total_cost = $amount + $fee_amount;
        if ($user->balance < $total_cost) {
            $error_message = __('Insufficient balance');
            return response()->json([
                'status' => 'error',
                'message' => $error_message
            ], 422);
        }


        // covert from website currency to usd
        $website_currency = getSetting('currency');
        $conversion = rateConverter($amount, $website_currency, 'USD', 'stock');

        if ($conversion['status'] == 'error') {
            $error_message = __('Something went wrong');
            return response()->json([
                'status' => 'error',
                'message' => $error_message
            ], 422);
        }

        $amount_usd = $conversion['converted_amount'];

        $ticker = strtoupper($request->ticker);

        $lozand = new LozandServices();
        $ticker_data = $lozand->ticker($ticker);

        if ($ticker_data['status'] == 'error') {
            $error_message = $ticker_data['message'] ?? __('Something went wrong');
            return response()->json([
                'status' => 'error',
                'message' => $error_message
            ], 422);
        }

        $ticker_data = $ticker_data['data'];

        //calculate shares
        $shares = $amount_usd / $ticker_data['current_price'];



        // debit the user
        $user->refresh();
        $new_balance = $user->balance - $total_cost;
        $user->balance = $new_balance;
        $user->save();

        // record transaction
        $reference = \Str::orderedUuid();
        $description = 'Stock Purchase';
        recordTransaction($user, $total_cost, $website_currency, $amount_usd, "USD", $conversion['exchange_rate'], "debit", "completed", $reference, $description, $new_balance);

        // store the stock purchase
        $user_holdings_on_ticker = StockHolding::where('user_id', $user->id)->where('ticker', $ticker)->first();

        if ($user_holdings_on_ticker) {
            // calculate average price
            $average_price = ($user_holdings_on_ticker->average_price * $user_holdings_on_ticker->shares + $amount_usd) / ($user_holdings_on_ticker->shares + $shares);
            $user_holdings_on_ticker->average_price = $average_price;
            $user_holdings_on_ticker->shares += $shares;
            $user_holdings_on_ticker->save();
        } else {
            $user_holdings_on_ticker = new StockHolding();
            $user_holdings_on_ticker->user_id = $user->id;
            $user_holdings_on_ticker->ticker = $ticker;
            $user_holdings_on_ticker->shares = $shares;
            $user_holdings_on_ticker->average_price = $ticker_data['current_price'];
            $user_holdings_on_ticker->save();
        }

        // record history
        $holding_history = new StockHoldingHistory();

        $holding_history->user_id = $user->id;
        $holding_history->stock_holding_id = $user_holdings_on_ticker->id;
        $holding_history->ticker = $ticker;
        $holding_history->shares = $shares;
        $holding_history->price_at_action = $ticker_data['current_price'];
        $holding_history->amount = $amount;
        $holding_history->amount_usd = $amount_usd;
        $holding_history->fee_amount = $fee_amount;
        $holding_history->fee_amount_percent = $stock_purchase_fee_percent;
        $holding_history->transaction_type = 'buy';
        $holding_history->save();



        $title = 'Stock Purchased';
        $body = __('You have successfully purchased :shares shares of :ticker at a price of :price', [
            'shares' => $shares,
            'ticker' => $ticker,
            'price' => $ticker_data['current_price']
        ]);
        recordNotificationMessage($user, $title, $body);

        $custom_subject = "Stock Purchased";
        $custom_message = "Your stock purchase order has been completed successfully, you can view the details of your purchase in the transaction history.";
        sendStockEmail($custom_subject, $custom_message, $holding_history);

        return response()->json([
            'status' => 'success',
            'message' => __('Stock purchased successfully'),
            'redirect' => route('user.capital-instruments.stocks')
        ], 200);
    }



    // sell stock (POST)
    public function sellStock(Request $request, $ticker)
    {

        $stock_sell_fee_percent = getSetting('stock_sale_fee_percent', 0);

        $request->validate([
            'shares' => 'required|numeric|gt:0',
        ]);

        $shares_to_sell = $request->shares;
        $ticker = strtoupper($ticker);
        $user = auth()->user();

        // 1. Check User Holding
        $holding = StockHolding::where('user_id', $user->id)
            ->where('ticker', $ticker)
            ->first();

        if (!$holding || $holding->shares < $shares_to_sell) {
            return response()->json([
                'status' => 'error',
                'message' => __('Insufficient shares.')
            ], 422);
        }

        // 2. Fetch Real-time Price
        $lozand = new LozandServices();
        $ticker_data = $lozand->ticker($ticker);

        if ($ticker_data['status'] == 'error') {
            $error_message = $ticker_data['message'] ?? __('Unable to fetch stock price.');
            return response()->json([
                'status' => 'error',
                'message' => $error_message
            ], 422);
        }
        $current_price = $ticker_data['data']['current_price'];

        // 3. Calculate Values
        $amount_usd = $shares_to_sell * $current_price;

        $website_currency = getSetting('currency');

        // Convert USD Amount to Website Currency
        $conversion = rateConverter($amount_usd, 'USD', $website_currency, 'stock');

        if ($conversion['status'] == 'error') {
            return response()->json([
                'status' => 'error',
                'message' => __('Currency conversion failed')
            ], 422);
        }

        $amount_website_currency = $conversion['converted_amount'];
        $exchange_rate = $conversion['exchange_rate'];

        // Fee calculation (on Website Currency Amount)
        $fee_amount_website_currency = $amount_website_currency * ($stock_sell_fee_percent / 100);
        $total_credit_website_currency = $amount_website_currency - $fee_amount_website_currency;

        // Calculate USD equivalents for history
        $fee_amount_usd = $amount_usd * ($stock_sell_fee_percent / 100);
        $total_credit_usd = $amount_usd - $fee_amount_usd;

        // 4. Credit User
        $user->refresh();

        // Conversion logic moved up

        $user->balance += $total_credit_website_currency;
        $user->save();
        $new_balance = $user->balance;

        // 5. Record Transaction
        $reference = \Str::orderedUuid();
        $description = 'Stock Sale: ' . $ticker;

        recordTransaction($user, $total_credit_website_currency, $website_currency, $total_credit_usd, "USD", $exchange_rate, "credit", "completed", $reference, $description, $new_balance);

        // 6. Update Holding
        $holding->shares -= $shares_to_sell;
        // Float precision safety
        if ($holding->shares < 0.00000001) {
            $holding->shares = 0;
        }
        $holding->save();

        // 7. Record History
        $holding_history = new StockHoldingHistory();
        $holding_history->user_id = $user->id;
        $holding_history->stock_holding_id = $holding->id;
        $holding_history->ticker = $ticker;
        $holding_history->shares = $shares_to_sell;
        $holding_history->price_at_action = $current_price;
        $holding_history->amount = $total_credit_website_currency;
        $holding_history->amount_usd = $total_credit_usd;
        $holding_history->fee_amount = $fee_amount_website_currency;
        $holding_history->fee_amount_percent = $stock_sell_fee_percent;
        $holding_history->transaction_type = 'sell';
        $holding_history->save();

        // 8. Send Notification & Email
        $title = __('Stock Sold');
        $body = __('You have successfully sold :shares shares of :ticker at a price of :price', [
            'shares' => $shares_to_sell,
            'ticker' => $ticker,
            'price' => $current_price
        ]);
        recordNotificationMessage($user, $title, $body);

        $custom_subject = __('Stock Sale');
        $custom_message = __('Your stock sale order has been completed successfully.');
        sendStockEmail($custom_subject, $custom_message, $holding_history);

        return response()->json([
            'status' => 'success',
            'message' => __('Stock sold successfully'),
            'redirect' => route('user.capital-instruments.stocks')
        ], 200);
    }

}
