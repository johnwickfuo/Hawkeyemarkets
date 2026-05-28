<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SelfTradingController extends Controller
{
    public function futuresTrading($ticker = null)
    {
        // check if futures module is enabled
        if (!moduleEnabled('futures_module')) {
            abort(403, __("Futures Trading is disabled"));
        }
        $current_ticker = $ticker ?? 'BTCUSDT';
        $page_title = __("Futures Trading");
        $page_description = __("Trade futures on :name. Access the most popular futures contracts with competitive leverage and tight spreads.", ['name' => getSetting('name')]);
        $template = config('site.template');
        $all_crypto_tickers = [];
        $last_error_message = null;
        $current_ticker_info = [];
        $lozandServices = new \App\Services\LozandServices();

        $get_all_crypto_tickers = $lozandServices->futureTickers();
        if ($get_all_crypto_tickers['status'] !== 'success') {
            $last_error_message = $get_all_crypto_tickers['message'];
        } else {
            $all_crypto_tickers = $get_all_crypto_tickers['data'];
            foreach ($all_crypto_tickers as $ticker) {
                if ($ticker['ticker'] == $current_ticker) {
                    $current_ticker_info = $ticker;
                    break;
                }
            }
        }

        $recent_trades = [];
        $get_recent_trades = $lozandServices->futuresRecentTrades($current_ticker);
        if ($get_recent_trades['status'] !== 'success') {
            $last_error_message = $get_recent_trades['message'];
        } else {
            $recent_trades = $get_recent_trades['data'];
        }

        $order_book = [];
        $get_order_book = $lozandServices->futuresOrderBook($current_ticker);
        if ($get_order_book['status'] !== 'success') {
            $last_error_message = $get_order_book['message'];
        } else {
            $order_book = $get_order_book['data'];
        }

        $add_available = 0;

        if (request()->ajax()) {
            return view("templates.{$template}.blades.pages.self-trading.futures-trading-inner", compact(
                'page_title',
                'page_description',
                'all_crypto_tickers',
                'last_error_message',
                'current_ticker_info',
                'current_ticker',
                'recent_trades',
                'order_book',
                'add_available'
            ));
        }

        return view("templates.{$template}.blades.pages.self-trading.futures-trading", compact(
            'page_title',
            'page_description',
            'all_crypto_tickers',
            'last_error_message',
            'current_ticker_info',
            'current_ticker',
            'recent_trades',
            'order_book',
            'add_available'
        ));
    }

    public function marginTrading($ticker = null)
    {
        // check if margin module is enabled
        if (!moduleEnabled('margin_module')) {
            abort(403, __("Margin Trading is disabled"));
        }

        $current_ticker = $ticker ?? 'BTCUSDT';
        $page_title = __("Margin Trading");
        $page_description = __("Trade margin on :name. Access the most popular margin contracts with competitive leverage and tight spreads.", ['name' => getSetting('name')]);
        $template = config('site.template');
        $all_margin_tickers = [];
        $last_error_message = null;
        $current_ticker_info = [];
        $lozandServices = new \App\Services\LozandServices();

        $get_all_margin_tickers = $lozandServices->margins();
        if ($get_all_margin_tickers['status'] !== 'success') {
            $last_error_message = $get_all_margin_tickers['message'];
        } else {
            $all_margin_tickers = $get_all_margin_tickers['data'];
            foreach ($all_margin_tickers as $ticker) {
                if ($ticker['ticker'] == $current_ticker) {
                    $current_ticker_info = $ticker;
                    break;
                }
            }
        }

        $recent_trades = [];
        $get_recent_trades = $lozandServices->marginRecentTrades($current_ticker);
        if ($get_recent_trades['status'] !== 'success') {
            $last_error_message = $get_recent_trades['message'];
        } else {
            $recent_trades = $get_recent_trades['data'];
        }

        $order_book = [];
        $get_order_book = $lozandServices->marginOrderBook($current_ticker);
        if ($get_order_book['status'] !== 'success') {
            $last_error_message = $get_order_book['message'];
        } else {
            $order_book = $get_order_book['data'];
        }

        $add_available = 0;

        if (request()->ajax()) {
            return view("templates.{$template}.blades.pages.self-trading.margin-trading-inner", compact(
                'page_title',
                'page_description',
                'all_margin_tickers',
                'last_error_message',
                'current_ticker_info',
                'current_ticker',
                'recent_trades',
                'order_book',
                'add_available'
            ));
        }

        return view("templates.{$template}.blades.pages.self-trading.margin-trading", compact(
            'page_title',
            'page_description',
            'all_margin_tickers',
            'last_error_message',
            'current_ticker_info',
            'current_ticker',
            'recent_trades',
            'order_book',
            'add_available'
        ));
    }

    public function forexTrading($ticker = null)
    {
        // check if forex module is enabled
        if (!moduleEnabled('forex_module')) {
            abort(403, __("Forex Trading is disabled"));
        }

        $current_ticker = $ticker ?? 'EUR_USD';
        $current_ticker_formatted = str_replace('_', '/', $current_ticker);
        $page_title = __("Forex Trading");
        $page_description = __("Trade forex on :name. Fast, reliable, and secure forex trading with competitive spreads and tight margins.", ['name' => getSetting('name')]);
        $template = config('site.template');
        $forex_tickers = [];
        $current_ticker_info = [];
        $last_error_message = null;
        $lozandServices = new \App\Services\LozandServices();

        $get_forex_tickers = $lozandServices->forexTickers();
        if ($get_forex_tickers['status'] !== 'success') {
            $last_error_message = $get_forex_tickers['message'];
        } else {
            $forex_tickers = $get_forex_tickers['data'];
            $current_ticker_info = collect($forex_tickers)->firstWhere('s', $current_ticker_formatted);
        }

        // Hardcode user balance to 0 for preview
        $balance = 0;
        $equity = 0;
        $margin_level = 0;
        $userLevel = __("VIP Trader");
        $accountType = __("Live");

        if (request()->ajax()) {
            return view("templates.{$template}.blades.pages.self-trading.forex-trading-inner", compact(
                'page_title',
                'page_description',
                'current_ticker',
                'current_ticker_formatted',
                'forex_tickers',
                'current_ticker_info',
                'last_error_message',
                'balance',
                'equity',
                'margin_level',
                'userLevel',
                'accountType'
            ));
        }

        return view("templates.{$template}.blades.pages.self-trading.forex-trading", compact(
            'page_title',
            'page_description',
            'current_ticker',
            'current_ticker_formatted',
            'forex_tickers',
            'current_ticker_info',
            'last_error_message',
            'balance',
            'equity',
            'margin_level',
            'userLevel',
            'accountType'
        ));
    }
}
