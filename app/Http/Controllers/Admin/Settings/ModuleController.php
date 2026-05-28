<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Define the available modules and their default configuration.
     * This serves as the source of truth for metadata (name, description, menu_search).
     */
    private function getAvailableModules()
    {
        $modules = getSetting('modules');
        if ($modules === null) {
            return [];
        }
        return json_decode($modules, true);
    }

    //index
    public function index()
    {
        $page_title = __('Modules');
        $template = config('site.template');
        $modules = $this->getAvailableModules();

        return view("templates.{$template}.blades.admin.settings.modules", compact(
            'page_title',
            'modules',
            'template'
        ));
    }

    public function update(Request $request)
    {
        $availableModules = $this->getAvailableModules();
        $storedModules = json_decode(getSetting('modules'), true) ?? [];
        $inputModules = $request->input('modules', []);

        // Base modules for saving
        $modulesToSave = $availableModules;

        foreach ($modulesToSave as $key => &$module) {
            // Apply stored status first (to preserve existing state for modules not in the request)
            if (isset($storedModules[$key]['status'])) {
                $module['status'] = $storedModules[$key]['status'];
            }

            // Apply new status from request
            if (isset($inputModules[$key]) && $inputModules[$key] === 'enabled') {
                $module['status'] = 'enabled';
            } else {
                $module['status'] = 'disabled';
            }

            // Sync with MenuItem table
            if (isset($module['menu_search'])) {
                $status = ($module['status'] === 'enabled');
                $searches = $module['menu_search']; // Now guaranteed to be array of arrays

                foreach ($searches as $search) {
                    MenuItem::where($search['column'], 'like', '%' . $search['term'] . '%')
                        ->update(['is_active' => $status]);
                }
            }

        }

        updateSetting('modules', $modulesToSave); //moved up because of cache

        // Update some user menu items manaully
        $self_trading_menu_status = true;
        //get from modules to save
        $forex_module = $modulesToSave['forex_module']['status'] ?? 'disabled';
        $futures_module = $modulesToSave['futures_module']['status'] ?? 'disabled';
        $margin_module = $modulesToSave['margin_module']['status'] ?? 'disabled';
        if ($forex_module === 'disabled' && $futures_module === 'disabled' && $margin_module === 'disabled') {
            $self_trading_menu_status = false;
        }

        MenuItem::where('label', 'like', '%Self Trading%')
            ->update(['is_active' => $self_trading_menu_status]);


        $capital_instruments_menu_status = true;
        //get from modules to save
        $stock_module = $modulesToSave['stock_module']['status'] ?? 'disabled';
        $etf_module = $modulesToSave['etf_module']['status'] ?? 'disabled';
        $bonds_module = $modulesToSave['bonds_module']['status'] ?? 'disabled';
        if ($stock_module === 'disabled' && $etf_module === 'disabled' && $bonds_module === 'disabled') {
            $capital_instruments_menu_status = false;
        }

        MenuItem::where('label', 'like', '%Capital Instruments%')
            ->update(['is_active' => $capital_instruments_menu_status]);


        // Remove menu from cache
        cache()->forget('admin_menu_items');
        cache()->forget('user_menu_items');

        file_put_contents(public_path('assets/json/modules.json'), json_encode($modulesToSave, JSON_PRETTY_PRINT));



        return response()->json(['message' => __('Modules updated successfully')]);
    }

}
