<?php

namespace App\Http\Controllers\User\Trading;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommodityController extends Controller
{
    public function index()
    {
        $page_title = "Commodity Trading";
        $template = config('site.template');

        return view("templates.{$template}.blades.user.trading.commodity", compact('page_title'));
    }
}
