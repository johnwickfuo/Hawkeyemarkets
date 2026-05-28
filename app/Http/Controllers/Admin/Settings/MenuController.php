<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class MenuController extends Controller
{
    public function index()
    {
        $page_title = __('Menu Settings');
        $template = config('site.template');

        // Fetch menu items grouped by type and hierarchy
        $user_menus = MenuItem::type('user')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->with([
                'children' => function ($q) {
                    $q->orderBy('sort_order');
                }
            ])
            ->get();

        $admin_menus = MenuItem::type('admin')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->with([
                'children' => function ($q) {
                    $q->orderBy('sort_order');
                }
            ])
            ->get();

        return view('templates.' . $template . '.blades.admin.settings.menu', compact(
            'page_title',
            'template',
            'user_menus',
            'admin_menus'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menu_items,id',
            'is_active' => 'required|boolean',
        ]);

        $menu = MenuItem::findOrFail($request->menu_id);
        $menu->is_active = $request->is_active;
        $menu->save();

        // Clear Caches
        cache()->forget('admin_menu_items');
        cache()->forget('user_menu_items');

        return response()->json([
            'status' => 'success',
            'message' => __('Menu visibility updated.')
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|in:admin,user',
            'route_name' => 'nullable|required_without:url|string|max:255',
            'url' => 'nullable|required_without:route_name|string|max:255',
            'icon' => 'nullable|string',
            'parent_id' => 'nullable|exists:menu_items,id',
            'sort_order' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        // Ensure only one is set
        if ($request->filled('route_name')) {
            // Check if route exists
            if (!Route::has($request->route_name)) {
                return back()->withInput()->withErrors(['route_name' => __('The specified route name does not exist.')]);
            }
            $data['url'] = null;
        } else {
            $data['route_name'] = null;
        }

        MenuItem::create($data);

        // Clear Caches
        cache()->forget('admin_menu_items');
        cache()->forget('user_menu_items');

        return back()->with('success', __('New menu item created.'));
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*' => 'required|exists:menu_items,id'
        ]);

        foreach ($request->items as $index => $id) {
            MenuItem::where('id', $id)->update(['sort_order' => $index]);
        }

        // Clear Caches
        cache()->forget('admin_menu_items');
        cache()->forget('user_menu_items');

        return response()->json([
            'status' => 'success',
            'message' => __('Order updated successfully.')
        ]);
    }
}
