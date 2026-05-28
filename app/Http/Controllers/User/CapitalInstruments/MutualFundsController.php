<?php

namespace App\Http\Controllers\User\CapitalInstruments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MutualFundsController extends Controller
{
    public function __construct()
    {
        if (!moduleEnabled('mutual_fund_module')) {
            abort(404);
        }
    }
    public function index()
    {
        $page_title = "Mutual Funds";
        $template = config('site.template');

        return view("templates.{$template}.blades.user.capital-instruments.mutual-funds", compact('page_title'));
    }
}
