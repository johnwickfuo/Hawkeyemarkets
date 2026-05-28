<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function index()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        $page_title = "Admin Login";
        $template = config('site.template');
        $login_methods = getSetting('login_methods');

        if (!is_array($login_methods)) {
            $login_methods = json_decode($login_methods, true);
        }

        // enable all social logins for admin in sandbox mode so users will see all available options
        if (config('app.env') === 'sandbox') {
            foreach ($login_methods as $key => $value) {
                $login_methods[$key]['status'] = 'enabled';
            }
        } else {
            //force enable only email login
            foreach ($login_methods as $key => $value) {
                if ($key !== 'email') {
                    $login_methods[$key]['status'] = 'disabled';
                }
            }
        }



        $enabled_methods = array_filter($login_methods, function ($method) {
            return $method['status'] === 'enabled';
        });

        $show_email_only = count($enabled_methods) === 1 && isset($enabled_methods['email']);

        return view("templates." . $template . ".blades.admin.auth.login", compact('page_title', 'login_methods', 'show_email_only'));
    }

    /**
     * Validate and log the admin in.
     */
    public function loginValidate(Request $request)
    {


        /**
         * SANDBOX MODE
         * In the sandbox mode, we disable login via email and password
         * Users can login with google auth if google auth is configured
         */


        if (config('app.env') === 'sandbox') {
            return response()->json([
                'status' => 'error',
                'message' => __('Website in sandbox mode, login with google to continue'),
            ]);
        }


        $google_recaptcha = getSetting('google_recaptcha');

        $rules = [
            'login' => 'required',
            'password' => 'required',
        ];

        if ($google_recaptcha === 'enabled') {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $validated = $request->validate($rules);
        $remember = $request->has('remember');

        $loginField = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (
            Auth::guard('admin')->attempt([
                $loginField => $validated['login'],
                'password' => $validated['password'],
            ], $remember)
        ) {

            $request->session()->regenerate();

            $admin = Auth::guard('admin')->user();

            // Set locale to admin's language
            App::setLocale($admin->lang);
            Session::put('locale', $admin->lang);

            // check if the login_otp is enabled
            if (getSetting('login_otp') === 'enabled') {
                $otp_code = mt_rand(100000, 999999);
                session()->put('admin_login_otp_code', $otp_code);
                session()->put('admin_login_otp_expires_at', now()->addMinutes(15));

                $message = __('We detected a secure login attempt on your :site account. To continue, please use the verification code below:', ['site' => getSetting('site_name')]);
                $subject = __('Admin Login verification OTP');
                sendOtpVerificationEmail($admin->name, $admin->email, $otp_code, $request->ip(), $request->userAgent(), $message, $subject);

                return response()->json([
                    'status' => 'success',
                    'message' => __('OTP sent to your email'),
                    'redirect' => route('admin.login.otp')
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => __('Login successful'),
                'redirect' => route('admin.dashboard')
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => __('Invalid credentials')
        ], 422);
    }

    /**
     * Admin OTP Page
     */
    public function otp()
    {
        if (session()->has('admin_otp_verified')) {
            return redirect()->route('admin.dashboard');
        }

        $page_title = __('Admin Login OTP');
        $throttle = false;

        if (Auth::guard('admin')->check()) {
            if (cache()->has('admin_resend_throttle_' . Auth::guard('admin')->id())) {
                $throttle = true;
            }
        }

        $template = config('site.template');

        return view('templates.' . $template . '.blades.admin.auth.otp', compact(
            'page_title',
            'throttle'
        ));
    }

    /**
     * Validate Admin OTP
     */
    public function validateOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|numeric',
        ]);

        if (!session()->has('admin_login_otp_code')) {
            return response()->json([
                'status' => 'error',
                'message' => __('Session expired. Please login again.'),
                'redirect' => route('admin.login')
            ], 422);
        }

        if (now()->greaterThan(session('admin_login_otp_expires_at'))) {
            return response()->json([
                'status' => 'error',
                'message' => __('OTP has expired. Please resend.')
            ], 422);
        }

        $otp_code = (int) $request->otp_code;

        if ($otp_code == session('admin_login_otp_code')) {
            session()->forget('admin_login_otp_code');
            session()->forget('admin_login_otp_expires_at');
            session()->put('admin_otp_verified', true);
            session()->save();
            //redirect url
            $redirectUrl = session('admin_redirect_url') ?? route('admin.dashboard');
            session()->forget('admin_redirect_url');
            return response()->json([
                'status' => 'success',
                'message' => __('Login successful.'),
                'redirect' => $redirectUrl
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => __('Invalid OTP code.')
        ], 422);
    }

    /**
     * Resend OTP for Admin
     */
    public function resendOtp(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return response()->json([
                'status' => 'error',
                'message' => __('Admin not found.')
            ], 404);
        }

        if (cache()->has('admin_resend_throttle_' . $admin->id)) {
            return response()->json([
                'status' => 'error',
                'message' => __('Please wait before requesting a new code')
            ], 422);
        }

        $otp_code = mt_rand(100000, 999999);
        session()->put('admin_login_otp_code', $otp_code);
        session()->put('admin_login_otp_expires_at', now()->addMinutes(15));

        $message = __('We detected a secure login attempt on your :site account. To continue, please use the verification code below:', ['site' => getSetting('site_name')]);
        $subject = __('Admin Login verification OTP');
        sendOtpVerificationEmail($admin->name, $admin->email, $otp_code, $request->ip(), $request->userAgent(), $message, $subject);

        cache()->put('admin_resend_throttle_' . $admin->id, true, now()->addSeconds(60));


        return response()->json([
            'status' => 'success',
            'message' => __('Verification code resent successfully.')
        ], 200);
    }

    /**
     * Log the admin out.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        session()->forget('admin_otp_verified');

        if (config('app.env') === 'sandbox') {
            session()->forget('sandbox_user');
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'status' => 'success',
            'message' => __('Logout successful.')
        ], 200);
    }

    /**
     * Show the forgot password form.
     */
    public function forgotPassword()
    {
        $page_title = __('Forgot Password');
        $template = config('site.template');

        return view('templates.' . $template . '.blades.admin.auth.passwords.email', compact('page_title'));
    }

    /**
     * Send reset code to admin.
     */
    public function sendResetCode(Request $request)
    {
        $google_recaptcha = getSetting('google_recaptcha');

        $rules = [
            'email' => 'required|email|exists:admins,email',
        ];

        if ($google_recaptcha === 'enabled') {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $request->validate($rules);

        $admin = Admin::where('email', $request->email)->first();

        $otp_code = mt_rand(100000, 999999);
        session()->put('admin_password_reset_otp_code', $otp_code);
        session()->put('admin_password_reset_email', $admin->email);
        session()->put('admin_password_reset_otp_expires_at', now()->addMinutes(15));

        $message = __('We received a request to reset your admin password. Use the verification code below to proceed:');
        $subject = __('Admin Password Reset OTP');
        sendOtpVerificationEmail($admin->name, $admin->email, $otp_code, $request->ip(), $request->userAgent(), $message, $subject);

        return response()->json([
            'status' => 'success',
            'message' => __('OTP sent to your email'),
            'redirect' => route('admin.forgot-password.otp')
        ]);
    }

    /**
     * Show OTP verification form for password reset.
     */
    public function resetOtp()
    {
        if (!session()->has('admin_password_reset_otp_code')) {
            return redirect()->route('admin.forgot-password');
        }

        $page_title = __('Verify OTP');
        $throttle = false;
        // Simplified throttle for reset
        $template = config('site.template');

        return view('templates.' . $template . '.blades.admin.auth.passwords.otp', compact('page_title', 'throttle'));
    }

    /**
     * Validate reset OTP.
     */
    public function validateResetOtp(Request $request)
    {
        $google_recaptcha = getSetting('google_recaptcha');

        $rules = [
            'otp_code' => 'required|numeric',
        ];

        if ($google_recaptcha === 'enabled') {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $request->validate($rules);

        if (!session()->has('admin_password_reset_otp_code')) {
            return response()->json([
                'status' => 'error',
                'message' => __('Session expired. Please try again.'),
                'redirect' => route('admin.forgot-password')
            ], 422);
        }

        if (now()->greaterThan(session('admin_password_reset_otp_expires_at'))) {
            return response()->json([
                'status' => 'error',
                'message' => __('OTP has expired. Please try again.')
            ], 422);
        }

        if ($request->otp_code == session('admin_password_reset_otp_code')) {
            session()->put('admin_password_reset_otp_verified', true);
            return response()->json([
                'status' => 'success',
                'message' => __('OTP verified successfully.'),
                'redirect' => route('admin.reset-password')
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => __('Invalid OTP code.')
        ], 422);
    }

    /**
     * Show reset password form.
     */
    public function resetPasswordForm()
    {
        if (!session()->has('admin_password_reset_otp_verified')) {
            return redirect()->route('admin.forgot-password');
        }

        $page_title = __('Reset Password');
        $template = config('site.template');

        return view('templates.' . $template . '.blades.admin.auth.passwords.reset', compact('page_title'));
    }

    /**
     * Update admin password.
     */
    public function updatePassword(Request $request)
    {
        if (!session()->has('admin_password_reset_otp_verified')) {
            return response()->json([
                'status' => 'error',
                'message' => __('Unauthorized. Please verify OTP first.'),
                'redirect' => route('admin.forgot-password')
            ], 403);
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $admin = Admin::where('email', session('admin_password_reset_email'))->first();
        if (!$admin) {
            return response()->json([
                'status' => 'error',
                'message' => __('Admin not found.')
            ], 404);
        }

        $admin->password = Hash::make($request->password);
        $admin->save();

        session()->forget([
            'admin_password_reset_otp_code',
            'admin_password_reset_email',
            'admin_password_reset_otp_expires_at',
            'admin_password_reset_otp_verified'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => __('Password updated successfully. You can now login.'),
            'redirect' => route('admin.login')
        ], 200);
    }

    /**
     * Redirect to Google for authentication.
     */
    public function redirectToGoogle($provider)
    {

        if ($provider !== 'google') {
            return redirect()->route('admin.login')->with('error', __('Website in sandbox mode, use google login'));
        }

        // sandbox mode
        if (config('app.env') !== 'sandbox') {
            return redirect()->route('admin.login')->with('error', __('Google login can only be used for admin login in sandbox mode'));
        }

        return Socialite::driver('google')
            ->redirectUrl(route('admin.login.google.callback', $provider))
            ->redirect();
    }

    /**
     * Handle the Google callback.
     */
    public function handleGoogleCallback()
    {
        // sandbox mode
        if (config('app.env') !== 'sandbox') {
            return redirect()->route('admin.login')->with('error', __('Google login can only be used for admin login in sandbox mode'));
        }

        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(route('admin.login.google.callback', 'google'))
                ->user();

            if (config('app.env') === 'sandbox') {
                // In sandbox mode, log in as the first admin
                logSandBoxUsers($googleUser->getEmail(), $googleUser->getName());
                $admin = Admin::first();
                if (!$admin) {
                    return redirect()->route('admin.login')->with('error', __('No admin accounts available.'));
                }
            } else {
                // In production, social login is disabled for admins (already handled by index() toggle)
                return redirect()->route('admin.login')->with('error', __('Social login is only available in sandbox mode.'));
            }

            Auth::guard('admin')->login($admin);
            session()->regenerate();

            // Set locale to admin's language
            App::setLocale($admin->lang);
            Session::put('locale', $admin->lang);

            return redirect()->route('admin.dashboard');
        } catch (\Exception $e) {
            return redirect()->route('admin.login')->with('error', __('Google authentication failed.'));
        }
    }
}