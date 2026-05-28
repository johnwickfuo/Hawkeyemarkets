<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\LozandServices;
use Illuminate\Http\Request;

class CapitalInstrumentController extends Controller
{
    //stocks
    public function stocks()
    {
        // check if stocks module is enabled
        if (!moduleEnabled('stock_module')) {
            abort(403, __("Stocks Trading is currently disabled check back later"));
        }
        $page_title = __('Stocks');
        $page_description = __('Buy and sell stocks on :name. Browse thousands of stocks from major exchanges worldwide.', ['name' => getSetting('name')]);
        $stocks = [];

        $lozand = new LozandServices();
        $stocks_request = $lozand->marketStocks();
        if ($stocks_request['status'] == 'success') {
            $stocks = $stocks_request['data'];
        }
        $message = $stocks_request['message'] ?? null;
        return view('templates.bento.blades.pages.capital-instruments.stocks', compact('page_title', 'page_description', 'stocks', 'message'));
    }


    // bonds
    public function bonds()
    {
        // check if bonds module is enabled
        if (!moduleEnabled('bonds_module')) {
            abort(403, __("Bonds Trading is currently disabled check back later"));
        }
        $page_title = __('Bonds');
        $page_description = __('Invest in bonds on :name. Access a wide range of government and corporate bonds with competitive yields.', ['name' => getSetting('name')]);
        $bonds = [];
        $lozand = new LozandServices();
        $bonds_request = $lozand->bonds();
        if ($bonds_request['status'] == 'success') {
            $bonds = $bonds_request['data'];
        }
        $message = $bonds_request['message'] ?? null;
        return view('templates.bento.blades.pages.capital-instruments.bonds', compact('page_title', 'page_description', 'bonds', 'message'));
    }


    // mutual funds
    public function mutualFunds()
    {
        // check if mutual funds module is enabled
        if (!moduleEnabled('mutual_fund_module')) {
            abort(403, __("Mutual Funds Trading is currently disabled check back later"));
        }
        $page_title = __('Mutual Funds');
        $page_description = __('Invest in mutual funds on :name. Access a wide range of mutual funds with competitive yields.', ['name' => getSetting('name')]);
        $mutual_funds = [];
        $lozand = new LozandServices();
        $mutual_funds_request = $lozand->mutualFunds();
        if ($mutual_funds_request['status'] == 'success') {
            $mutual_funds = $mutual_funds_request['data'];
        }
        $message = $mutual_funds_request['message'] ?? null;
        return view('templates.bento.blades.pages.capital-instruments.mutual-funds', compact('page_title', 'page_description', 'mutual_funds', 'message'));
    }


    // ETFs
    public function etfs()
    {
        // check if etfs module is enabled
        if (!moduleEnabled('etf_module')) {
            abort(403, __("ETFs Trading is currently disabled check back later"));
        }
        $page_title = __('ETFs');
        $page_description = __('Invest in ETFs on :name. Access a wide range of ETFs with competitive yields.', ['name' => getSetting('name')]);
        $etfs = [];
        $lozand = new LozandServices();
        $etfs_request = $lozand->marketEtfs();
        if ($etfs_request['status'] == 'success') {
            $etfs = $etfs_request['data'];
        }
        $message = $etfs_request['message'] ?? null;
        return view('templates.bento.blades.pages.capital-instruments.etfs', compact('page_title', 'page_description', 'etfs', 'message'));
    }



}
