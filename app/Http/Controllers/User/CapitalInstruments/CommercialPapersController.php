<?php

namespace App\Http\Controllers\User\CapitalInstruments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommercialPapersController extends Controller
{
    public function index()
    {
        $page_title = "Commercial Papers";
        $template = config('site.template');

        return view("templates.{$template}.blades.user.capital-instruments.commercial-papers", compact('page_title'));
    }
}
