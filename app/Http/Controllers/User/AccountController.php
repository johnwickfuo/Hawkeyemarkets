<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\GeoLocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * Show user profile settings.
     */
    public function profile()
    {
        $page_title = __('My Profile');
        $template = config('site.template');
        $user = Auth::user();

        return view("templates.$template.blades.user.account.profile", compact('page_title', 'template', 'user'));
    }

    /**
     * Update user profile settings.
     */
    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'lang' => 'required|string|max:10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Only allow username update if it's currently unset in DB (bypass accessor)
        if (empty($user->getRawOriginal('username'))) {
            $rules['username'] = [
                'required',
                'string',
                'alpha_dash',
                'min:3',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ];
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');

            // Extra precaution: check the client-reported extension
            $allowedExtensions = ['jpeg', 'png', 'jpg', 'gif'];
            if (!in_array(strtolower($photo->getClientOriginalExtension()), $allowedExtensions)) {
                return response()->json(['status' => 'error', 'message' => __('Invalid file extension.')], 422);
            }

            $extension = $photo->extension();
            $filename = time() . '.' . $extension;
            $photo->move(public_path('assets/images/users/'), $filename);
            $validated['photo'] = 'assets/images/users/' . $filename;

            // Delete old photo if it exists
            if ($user->photo && file_exists(public_path($user->photo))) {
                @unlink(public_path($user->photo));
            }
        }

        $user->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Profile updated successfully.'),
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
        $user = Auth::user();
        $guardName = Auth::getName(); // Default guard is web

        // Get active sessions
        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', session()->getId())
            ->orderBy('last_activity', 'desc')
            ->get()
            ->filter(function ($session) use ($guardName) {
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

        return view("templates.$template.blades.user.account.security", compact('page_title', 'template', 'sessions', 'user', 'current_location'));
    }

    /**
     * Update user password.
     */
    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Password updated successfully.'),
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
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();
        $guardName = Auth::getName();

        $otherSessions = DB::table('sessions')
            ->where('user_id', $user->id)
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
                'message' => __('Logged out from other devices successfully.'),
            ]);
        }

        return back()->with('success', __('Logged out from other devices successfully.'));
    }
}
