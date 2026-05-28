<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\ManagementTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TeamController extends Controller
{
    public function index()
    {
        $page_title = __('Management Team');
        $template = config('site.template');
        $teams = ManagementTeam::all();
        return view('templates.' . $template . '.blades.admin.settings.team', compact('page_title', 'teams', 'template'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:ceo,cto,coo,cmo,cfo,quant,others',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'role', 'description']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('assets/images/team'), $imageName);
            $data['image'] = $imageName;
        }

        ManagementTeam::create($data);

        return response()->json(['message' => __('Team member created successfully')]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:ceo,cto,coo,cmo,cfo,quant,others',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $team = ManagementTeam::findOrFail($id);
        $data = $request->only(['name', 'role', 'description']);

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($team->image && File::exists(public_path('assets/images/team/' . $team->image))) {
                File::delete(public_path('assets/images/team/' . $team->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('assets/images/team'), $imageName);
            $data['image'] = $imageName;
        }

        $team->update($data);

        return response()->json(['message' => __('Team member updated successfully')]);
    }

    public function delete($id)
    {
        $team = ManagementTeam::findOrFail($id);

        if ($team->image && File::exists(public_path('assets/images/team/' . $team->image))) {
            File::delete(public_path('assets/images/team/' . $team->image));
        }

        $team->delete();

        return response()->json(['message' => __('Team member deleted successfully')]);
    }
}
