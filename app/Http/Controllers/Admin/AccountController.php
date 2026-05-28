<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\GeoLocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    /**
     * Redirect to profile settings.
     */
    public function index()
    {
        return redirect()->route('admin.account.profile');
    }

    /**
     * Show the profile edit form.
     */
    public function profile()
    {
        $page_title = __('Profile Settings');
        $admin = Auth::guard('admin')->user();
        $template = config('site.template');

        return view("templates.$template.blades.admin.account.profile", compact('page_title', 'admin', 'template'));
    }

    /**
     * Update admin profile.
     */
    public function profileUpdate(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admins,username,' . $admin->id,
            'email' => 'required|email|max:255|unique:admins,email,' . $admin->id,
            'lang' => 'required|string|in:' . implode(',', array_keys(config('languages'))),
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $admin->name = $request->name;
        $admin->username = $request->username;
        $admin->email = $request->email;
        $admin->lang = $request->lang;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($admin->image) {
                Storage::disk('public')->delete('profile/' . $admin->image);
            }
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('profile', $filename, 'public');
            $admin->image = $filename;
        }

        $admin->save();

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Profile updated successfully.')
            ]);
        }

        return back()->with('success', __('Profile updated successfully.'));
    }

    /**
     * Show security settings.
     */
    public function security(GeoLocationService $geoLocationService)
    {
        $page_title = __('Security Settings');
        $template = config('site.template');
        $admin = Auth::guard('admin')->user();
        $guardName = Auth::guard('admin')->getName();

        // Get active sessions
        $sessions = DB::table('sessions')
            ->where('user_id', $admin->id)
            ->where('id', '!=', session()->getId())
            ->orderBy('last_activity', 'desc')
            ->get()
            ->filter(function ($session) use ($guardName) {
                // Decode payload to verify it belongs to this guard
                $payload = unserialize(base64_decode($session->payload));
                return isset($payload[$guardName]);
            })
            ->map(function ($session) use ($geoLocationService) {
                return (object) [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'location' => $geoLocationService->getLocation($session->ip_address),
                    'user_agent' => $session->user_agent,
                    'last_activity' => \Carbon\Carbon::createFromTimestamp($session->last_activity),
                ];
            });

        $current_location = $geoLocationService->getLocation(request()->ip());

        return view("templates.$template.blades.admin.account.security", compact('page_title', 'template', 'sessions', 'admin', 'current_location'));
    }

    /**
     * Update admin password.
     */
    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password:admin',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $admin = Auth::guard('admin')->user();
        $admin->password = Hash::make($request->password);
        $admin->save();

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Password updated successfully.')
            ]);
        }

        return back()->with('success', __('Password updated successfully.'));
    }

    /**
     * Logout from other devices.
     */
    public function logoutOtherDevices(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password:admin',
        ]);

        $admin = Auth::guard('admin')->user();
        $guardName = Auth::guard('admin')->getName();

        $otherSessions = DB::table('sessions')
            ->where('user_id', $admin->id)
            ->where('id', '!=', session()->getId())
            ->get();

        foreach ($otherSessions as $session) {
            $payload = unserialize(base64_decode($session->payload));
            if (isset($payload[$guardName])) {
                DB::table('sessions')->where('id', $session->id)->delete();
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Other sessions logged out successfully.')
            ]);
        }

        return back()->with('success', __('Other sessions logged out successfully.'));
    }
}
