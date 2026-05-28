<?php

namespace App\Http\Controllers\User\CapitalInstruments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TreasuryBillsController extends Controller
{
    public function index()
    {
        $page_title = "Treasury Bills";
        $template = config('site.template');

        return view("templates.{$template}.blades.user.capital-instruments.treasury-bills", compact('page_title'));
    }
}
