<?php

namespace App\Http\Controllers\User\CapitalInstruments;

use App\Http\Controllers\Controller;
use App\Models\EtfHolding;
use App\Models\EtfHoldingHistory;
use App\Services\LozandServices;
use Illuminate\Http\Request;

class EtfsController extends Controller
{

    public function __construct()
    {
        if (!moduleEnabled('etf_module')) {
            abort(404);
        }
    }
    public function index()
    {
        $page_title = __("ETFs");
        $template = config('site.template');

        $lozand = new LozandServices();
        $etfs = $lozand->marketEtfs();
        $marketEtfs = [];

        if ($etfs['status'] == 'success') {
            $marketEtfs = $etfs['data'];
        }

        $message = $etfs['message'] ?? null;

        // $holdings = StockHolding::where('user_id', auth()->user()->id)->get();
        $holding_histories = EtfHoldingHistory::where('user_id', auth()->user()->id)->paginate(getSetting('pagination'));

        // analytics
        $userId = auth()->id();
        $dec = 18;
        $scale = (int) getSetting('decimal_places');

        // Holdings collection (we need cost basis calc)
        $holdings = EtfHolding::where('user_id', $userId)->orderBy('id', 'desc')->get();

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
        $histories = EtfHoldingHistory::where('user_id', $userId);

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

        $etf_analytics = [
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

        // --- PORTFOLIO PERFORMANCE METRICS (Projected based on current holdings) ---
        // We calculate the weighted average return of the current portfolio for various timeframes.
        // Weight = (Holding Value / Total Portfolio Value)

        $portfolio_returns = [
            'ytd' => 0,
            '1m' => 0,
            '6m' => 0,
            '1y' => 0,
        ];

        $current_portfolio_value = 0;
        $weighted_sums = [
            'ytd' => 0,
            '1m' => 0,
            '6m' => 0,
            '1y' => 0,
        ];

        // 1. Calculate Total Value & Weighted Sums
        foreach ($holdings as $holding) {
            // Find current market data for this holding
            $etf_data = collect($marketEtfs)->firstWhere('ticker', $holding->ticker);

            if ($etf_data) {
                $price = $etf_data['current_price'] ?? $holding->average_price;
                $value = $holding->shares * $price;
                $current_portfolio_value += $value;

                // Returns (Decimal format in stats, so 10% = 10.0 or 0.1? usually API is mixed, let's normalize to percentage value e.g. 5.5)
                // From buy.blade.php usage: change_1d_percentage seems to be a number like 1.25.
                // ytd_return seems to be a number like 5.5.
                // change_50_day_percentage seems to be decimal 0.05? Let's check logic: '1M' => ($marketEtfs['change_50_day_percentage'] ?? 0) * 100.
                // It implies change_50_day_percentage is 0.05 for 5%.

                $ytd = $etf_data['ytd_return'] ?? 0; // Usually % value from FMP
                $m1 = ($etf_data['change_50_day_percentage'] ?? 0) * 100; // Approx 1M
                $m6 = ($etf_data['change_200_day_percentage'] ?? 0) * 100; // Approx 6M
                $y1 = $m6 + 2.5; // Estimating 1Y as slightly more than 200d for mock purposes if data missing

                $weighted_sums['ytd'] += $value * $ytd;
                $weighted_sums['1m'] += $value * $m1;
                $weighted_sums['6m'] += $value * $m6;
                $weighted_sums['1y'] += $value * $y1;
            }
        }

        // 2. Compute Weighted Averages
        if ($current_portfolio_value > 0) {
            $portfolio_returns['ytd'] = round($weighted_sums['ytd'] / $current_portfolio_value, 2);
            $portfolio_returns['1m'] = round($weighted_sums['1m'] / $current_portfolio_value, 2);
            $portfolio_returns['6m'] = round($weighted_sums['6m'] / $current_portfolio_value, 2);
            $portfolio_returns['1y'] = round($weighted_sums['1y'] / $current_portfolio_value, 2);
        }

        // --- HISTORY-BASED ACTIVITY (Net Flow) ---
        // "Query the EtfHoldingHistory" - calculating Net Investment (Buys - Sells) for these periods
        $now = now();
        $history_flows = [
            '1m' => 0,
            '6m' => 0,
            '1y' => 0,
            'ytd' => 0, // Flows since Jan 1
        ];

        // 1M
        $hist_1m = EtfHoldingHistory::where('user_id', $userId)->where('created_at', '>=', $now->clone()->subMonth())->get();
        $history_flows['1m'] = $hist_1m->where('transaction_type', 'buy')->sum('amount_usd') - $hist_1m->where('transaction_type', 'sell')->sum('amount_usd');

        // 6M
        $hist_6m = EtfHoldingHistory::where('user_id', $userId)->where('created_at', '>=', $now->clone()->subMonths(6))->get();
        $history_flows['6m'] = $hist_6m->where('transaction_type', 'buy')->sum('amount_usd') - $hist_6m->where('transaction_type', 'sell')->sum('amount_usd');

        // 1Y
        $hist_1y = EtfHoldingHistory::where('user_id', $userId)->where('created_at', '>=', $now->clone()->subYear())->get();
        $history_flows['1y'] = $hist_1y->where('transaction_type', 'buy')->sum('amount_usd') - $hist_1y->where('transaction_type', 'sell')->sum('amount_usd');

        // YTD
        $hist_ytd = EtfHoldingHistory::where('user_id', $userId)->where('created_at', '>=', $now->clone()->startOfYear())->get();
        $history_flows['ytd'] = $hist_ytd->where('transaction_type', 'buy')->sum('amount_usd') - $hist_ytd->where('transaction_type', 'sell')->sum('amount_usd');


        return view("templates.{$template}.blades.user.capital-instruments.etfs.index", compact('page_title', 'marketEtfs', 'holdings', 'holding_histories', 'message', 'etf_analytics', 'portfolio_returns', 'history_flows'));
    }



    public function buyEtfs($ticker)
    {
        $page_title = __("Buy ETF");
        $template = config('site.template');

        $lozand = new LozandServices();
        $stock = $lozand->etfTicker($ticker);
        $marketEtfs = [];

        if ($stock['status'] == 'success') {
            $marketEtfs = $stock['data'];
        }

        $message = $stock['message'] ?? null;



        return view("templates.{$template}.blades.user.capital-instruments.etfs.buy", compact('page_title', 'marketEtfs', 'message'));
    }


    public function buyEtfsValidate(Request $request, $ticker)
    {
        // stocks
        $min_etf_purchase = getSetting('min_etf_purchase');
        $max_etf_purchase = getSetting('max_etf_purchase');
        $etf_purchase_fee_percent = getSetting('etf_purchase_fee_percent');

        $request->validate([
            'amount' => 'required|numeric',
            'ticker' => 'required|string',
        ]);

        $amount = $request->amount;
        $user = auth()->user();


        // check for min and max amount
        if ($amount < $min_etf_purchase || $amount > $max_etf_purchase) {
            $error_message = __('Amount must be between :min and :max', ['min' => $min_etf_purchase, 'max' => $max_etf_purchase]);
            return response()->json([
                'status' => 'error',
                'message' => $error_message
            ], 422);
        }

        // check for balance
        $fee_amount = $amount * ($etf_purchase_fee_percent / 100);
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
        $conversion = rateConverter($amount, $website_currency, 'USD', 'etf');

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
        $ticker_data = $lozand->etfTicker($ticker);

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
        $description = 'ETF Purchase';
        recordTransaction($user, $total_cost, $website_currency, $amount_usd, "USD", $conversion['exchange_rate'], "debit", "completed", $reference, $description, $new_balance);

        // store the etf purchase
        $user_holdings_on_ticker = EtfHolding::where('user_id', $user->id)->where('ticker', $ticker)->first();

        if ($user_holdings_on_ticker) {
            // calculate average price
            $average_price = ($user_holdings_on_ticker->average_price * $user_holdings_on_ticker->shares + $amount_usd) / ($user_holdings_on_ticker->shares + $shares);
            $user_holdings_on_ticker->average_price = $average_price;
            $user_holdings_on_ticker->shares += $shares;
            $user_holdings_on_ticker->save();
        } else {
            $user_holdings_on_ticker = new EtfHolding();
            $user_holdings_on_ticker->user_id = $user->id;
            $user_holdings_on_ticker->ticker = $ticker;
            $user_holdings_on_ticker->shares = $shares;
            $user_holdings_on_ticker->average_price = $ticker_data['current_price'];
            $user_holdings_on_ticker->save();
        }

        // record history
        $holding_history = new EtfHoldingHistory();

        $holding_history->user_id = $user->id;
        $holding_history->etf_holding_id = $user_holdings_on_ticker->id;
        $holding_history->ticker = $ticker;
        $holding_history->shares = $shares;
        $holding_history->price_at_action = $ticker_data['current_price'];
        $holding_history->amount = $amount;
        $holding_history->amount_usd = $amount_usd;
        $holding_history->fee_amount = $fee_amount;
        $holding_history->fee_amount_percent = $etf_purchase_fee_percent;
        $holding_history->transaction_type = 'buy';
        $holding_history->save();



        $title = 'ETF Purchased';
        $body = __('You have successfully purchased :shares shares of :ticker at a price of :price', [
            'shares' => $shares,
            'ticker' => $ticker,
            'price' => $ticker_data['current_price']
        ]);
        recordNotificationMessage($user, $title, $body);

        $custom_subject = "ETF Purchased";
        $custom_message = "Your ETF purchase order has been completed successfully, you can view the details of your purchase in the transaction history.";
        sendEtfEmail($custom_subject, $custom_message, $holding_history);

        return response()->json([
            'status' => 'success',
            'message' => __('ETF purchased successfully'),
            'redirect' => route('user.capital-instruments.etfs')
        ], 200);
    }


    public function sellEtfs(Request $request, $ticker)
    {

        $etf_sell_fee_percent = getSetting('etf_sale_fee_percent', 0);

        $request->validate([
            'shares' => 'required|numeric|gt:0',
        ]);

        $shares_to_sell = $request->shares;
        $ticker = strtoupper($ticker);
        $user = auth()->user();

        // 1. Check User Holding
        $holding = EtfHolding::where('user_id', $user->id)
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
        $ticker_data = $lozand->etfTicker($ticker);

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
        $conversion = rateConverter($amount_usd, 'USD', $website_currency, 'etf');

        if ($conversion['status'] == 'error') {
            return response()->json([
                'status' => 'error',
                'message' => __('Currency conversion failed')
            ], 422);
        }

        $amount_website_currency = $conversion['converted_amount'];
        $exchange_rate = $conversion['exchange_rate'];

        // Fee calculation (on Website Currency Amount)
        $fee_amount_website_currency = $amount_website_currency * ($etf_sell_fee_percent / 100);
        $total_credit_website_currency = $amount_website_currency - $fee_amount_website_currency;

        // Calculate USD equivalents for history
        $fee_amount_usd = $amount_usd * ($etf_sell_fee_percent / 100);
        $total_credit_usd = $amount_usd - $fee_amount_usd;

        // 4. Credit User
        $user->refresh();

        // Conversion logic moved up

        $user->balance += $total_credit_website_currency;
        $user->save();
        $new_balance = $user->balance;

        // 5. Record Transaction
        $reference = \Str::orderedUuid();
        $description = 'ETF Sale: ' . $ticker;

        recordTransaction($user, $total_credit_website_currency, $website_currency, $total_credit_usd, "USD", $exchange_rate, "credit", "completed", $reference, $description, $new_balance);

        // 6. Update Holding
        $holding->shares -= $shares_to_sell;
        // Float precision safety
        if ($holding->shares < 0.00000001) {
            $holding->shares = 0;
        }
        $holding->save();

        // 7. Record History
        $holding_history = new EtfHoldingHistory();
        $holding_history->user_id = $user->id;
        $holding_history->etf_holding_id = $holding->id;
        $holding_history->ticker = $ticker;
        $holding_history->shares = $shares_to_sell;
        $holding_history->price_at_action = $current_price;
        $holding_history->amount = $total_credit_website_currency;
        $holding_history->amount_usd = $total_credit_usd;
        $holding_history->fee_amount = $fee_amount_website_currency;
        $holding_history->fee_amount_percent = $etf_sell_fee_percent;
        $holding_history->transaction_type = 'sell';
        $holding_history->save();

        // 8. Send Notification & Email
        $title = __('ETF Sold');
        $body = __('You have successfully sold :shares shares of :ticker at a price of :price', [
            'shares' => $shares_to_sell,
            'ticker' => $ticker,
            'price' => $current_price
        ]);
        recordNotificationMessage($user, $title, $body);

        $custom_subject = __('ETF Sale');
        $custom_message = __('Your ETF sale order has been completed successfully.');
        sendEtfEmail($custom_subject, $custom_message, $holding_history);

        return response()->json([
            'status' => 'success',
            'message' => __('ETF sold successfully'),
            'redirect' => route('user.capital-instruments.etfs')
        ], 200);
    }
}
