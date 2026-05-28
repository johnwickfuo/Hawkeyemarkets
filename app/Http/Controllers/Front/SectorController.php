<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\LozandServices;
use Illuminate\Http\Request;

class SectorController extends Controller
{
    public function index(Request $request)
    {
        $route_name = $request->route()->getName();
        // exploade and get the last
        $routes_name_array = explode('.', $route_name);
        $sector = $routes_name_array[array_key_last($routes_name_array)];

        $sectors = array_keys(config('interests'));

        $formated_sectors_array_keys = [];
        foreach ($sectors as $sector_key) {
            $formated_sector_key = str_replace(['_and_', '_'], ['-', '-'], $sector_key);
            $formated_sectors_array_keys[$formated_sector_key] = $sector_key;
        }


        if (!array_key_exists($sector, $formated_sectors_array_keys)) {
            abort(404);
        }


        $actual_array_key = $formated_sectors_array_keys[$sector];

        $template = config('site.template');

        $stocks = [];
        $etfs = [];
        $futures = [];

        $sector_data = config('sectors.' . $actual_array_key);
        switch ($actual_array_key) {
            case 'stocks_and_etfs':
                $page_title = "Stocks & ETFs";
                $page_description = __("Invest in stocks and ETFs with :name. We offer a wide range of stocks and ETFs to choose from.", ['name' => getSetting('name')]);
                $lozand = new LozandServices();
                $stocks_request = $lozand->marketStocks();

                if ($stocks_request['status'] == 'success') {
                    $stocks = $stocks_request['data'];
                }

                $etfs_request = $lozand->marketEtfs();

                if ($etfs_request['status'] == 'success') {
                    $etfs = $etfs_request['data'];
                }
                break;

            case 'crypto_assets':
                $page_title = "Crypto Assets";
                $page_description = __("Invest in crypto assets with :name. We offer a wide range of crypto assets to choose from.", ['name' => getSetting('name')]);
                $lozand = new LozandServices();
                $futures_request = $lozand->futureTickers();

                if ($futures_request['status'] == 'success') {
                    $futures = $futures_request['data'];
                }
                break;

            default:
                $page_title = ucwords(str_replace('_', ' ', $actual_array_key));
                $page_description = __("Invest in :sector with :name. We offer a wide range of :sector to choose from.", ['sector' => $page_title, 'name' => getSetting('name')]);
                break;
        }



        return view('templates.' . $template . '.blades.pages.sectors.' . $sector, compact(
            'page_title',
            'page_description',
            'sector',
            'sector_data',
            'stocks',
            'etfs',
            'futures',
        ));
    }
}
