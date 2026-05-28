<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Session;

class LoginController extends Controller
{
    //index
    public function index()
    {
        $page_title = "Sign In";
        $template = config('site.template');

        // Dynamic login methods
        $login_methods = json_decode(getSetting('login_methods'), true);

        if (config('app.env') == 'sandbox') {
            // change all to enabled
            foreach ($login_methods as $key => $method) {
                $login_methods[$key]['status'] = 'enabled';
            }
        }

        // Filter out disabled methods
        $login_methods = array_filter($login_methods, function ($method) {
            return isset($method['status']) && $method['status'] === 'enabled';
        });

        // if only email is enabled, show the email login form
        $only_email = count($login_methods) === 1 && isset($login_methods['email']);

        return view("templates.$template.blades.user.auth.login", compact(
            'page_title',
            'template',
            'login_methods',
            'only_email'
        ));
    }

    //login validate
    public function loginValidate(Request $request)
    {
        //in sandbox only login using google
        if (config('app.env') == 'sandbox') {
            return response()->json([
                'status' => 'error',
                'message' => __('Login with email is disabled in sandbox mode. Please login with Google.'),

            ], 401);
        }
        $google_recaptcha = getSetting('google_recaptcha');

        $rules = [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ];
        if ($google_recaptcha === 'enabled') {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $validated = $request->validate($rules);
        $remember = $request->has('remember');

        // Attempt login
        if (
            Auth::attempt([
                'email' => $validated['email'],
                'password' => $validated['password'],
            ], $remember)
        ) {

            // Prevent session fixation
            $user = Auth::user();
            $provider = $user->provider_name ?? 'email';
            $provider = strtolower($provider);

            // Restriction: Only allow email login if provider is null or 'email'
            if ($provider !== 'email') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return response()->json([
                    'status' => 'error',
                    'message' => __('This account was created using :provider. Please use the appropriate social login method.', ['provider' => ucfirst($user->provider ?? 'email')])
                ], 422);
            }

            // Block banned / suspended accounts before going any further
            if ($user->status !== 'active') {
                $message = match ($user->status) {
                    'banned' => __('Your account has been banned. Please contact support.'),
                    'suspended' => __('Your account has been suspended. Please contact support.'),
                    default => __('Your account is not active. Please contact support.'),
                };

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return response()->json([
                    'status' => 'error',
                    'message' => $message,
                ], 403);
            }

            // check if the login_otp is enabled
            if (getSetting('login_otp') === 'enabled') {
                $otp_code = mt_rand(100000, 999999);
                session()->put('login_otp_code', $otp_code);
                session()->put('login_otp_expires_at', now()->addMinutes(15));

                $message = __('We detected a secure login attempt on your :site account. To continue, please use the verification code below:', ['site' => getSetting('site_name')]);
                $subject = __('Login verification OTP');
                sendOtpVerificationEmail($user->first_name, $user->email, $otp_code, $request->ip(), $request->userAgent(), $message, $subject);

                return response()->json([
                    'status' => 'success',
                    'message' => __('OTP sent to your email'),
                    'redirect' => route('user.login.otp')
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => __('Login successful'),
                'redirect' => route('user.dashboard')
            ]);
        }

        // Login failed
        return response()->json([
            'status' => 'error',
            'message' => __('Invalid credentials')
        ], 422);
    }

    // OTP Page
    public function otp()
    {
        if (session()->has('user_otp_verified')) {
            return redirect()->route('user.dashboard');
        }

        $page_title = __('Login OTP');
        $template = config('site.template');
        $throttle = false;

        if (auth()->check()) {
            if (cache()->has('resend_throttle_' . auth()->id())) {
                $throttle = true;
            }
        }

        return view('templates.' . $template . '.blades.user.auth.otp', compact(
            'page_title',
            'template',
            'throttle'
        ));
    }

    // validate otp
    public function validateOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|numeric',
        ]);

        if (!session()->has('login_otp_code')) {
            return response()->json([
                'status' => 'error',
                'message' => __('Session expired. Please login again.'),
                'redirect' => route('user.login')
            ], 422);
        }

        if (now()->greaterThan(session('login_otp_expires_at'))) {
            return response()->json([
                'status' => 'error',
                'message' => __('OTP has expired. Please resend.')
            ], 422);
        }

        $otp_code = (int) $request->otp_code;

        if ($otp_code == session('login_otp_code')) {
            session()->forget('login_otp_code');
            session()->forget('login_otp_expires_at');
            session()->put('user_otp_verified', true);
            session()->save();
            //redirect url
            $redirectUrl = session('user_redirect_url') ?? route('user.dashboard');
            session()->forget('user_redirect_url');
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

    // resend otp
    public function resendOtp(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => __('User not found.')
            ], 404);
        }

        // Throttle check (e.g., 1 minute)
        if (cache()->has('resend_throttle_' . $user->id)) {
            return response()->json([
                'status' => 'error',
                'message' => __('Please wait before requesting a new code')
            ], 422);
        }

        $otp_code = mt_rand(100000, 999999);

        session()->put('login_otp_code', $otp_code);
        session()->put('login_otp_expires_at', now()->addMinutes(15));

        $message = __('We detected a secure login attempt on your :site account. To continue, please use the verification code below:', ['site' => getSetting('site_name')]);
        $subject = __('Login verification OTP');
        sendOtpVerificationEmail($user->first_name, $user->email, $otp_code, $request->ip(), $request->userAgent(), $message, $subject);

        cache()->put('resend_throttle_' . $user->id, true, now()->addSeconds(60));


        return response()->json([
            'status' => 'success',
            'message' => __('Verification code resent successfully.')
        ], 200);
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();
        session()->forget('user_otp_verified');
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

    // ─── Forgot Password ──────────────────────────────────────────────────────

    /**
     * Show the forgot password form.
     */
    public function forgotPassword()
    {
        $page_title = __('Forgot Password');
        $template = config('site.template');
        return view("templates.$template.blades.user.auth.passwords.email", compact('page_title', 'template'));
    }

    /**
     * Send OTP reset code to user's email.
     */
    public function sendResetCode(Request $request)
    {
        $google_recaptcha = getSetting('google_recaptcha');

        $rules = [
            'email' => 'required|email|exists:users,email',
        ];

        if ($google_recaptcha === 'enabled') {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $request->validate($rules);

        $user = User::where('email', $request->email)->first();

        $otp_code = mt_rand(100000, 999999);
        session()->put('user_password_reset_otp_code', $otp_code);
        session()->put('user_password_reset_email', $user->email);
        session()->put('user_password_reset_otp_expires_at', now()->addMinutes(15));

        $message = __('We received a request to reset your password. Use the verification code below to proceed:');
        $subject = __('Password Reset OTP');
        sendOtpVerificationEmail($user->first_name, $user->email, $otp_code, $request->ip(), $request->userAgent(), $message, $subject);

        return response()->json([
            'status' => 'success',
            'message' => __('OTP sent to your email'),
            'redirect' => route('user.forgot-password.otp')
        ]);
    }

    /**
     * Show OTP verification form for password reset.
     */
    public function resetOtp()
    {
        if (!session()->has('user_password_reset_otp_code')) {
            return redirect()->route('user.forgot-password');
        }

        $page_title = __('Verify OTP');
        $template = config('site.template');
        $throttle = false;

        return view("templates.$template.blades.user.auth.passwords.otp", compact('page_title', 'template', 'throttle'));
    }

    /**
     * Validate OTP for password reset.
     */
    public function validateResetOtp(Request $request)
    {
        $google_recaptcha = getSetting('google_recaptcha');

        $rules = ['otp_code' => 'required|numeric'];

        if ($google_recaptcha === 'enabled') {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $request->validate($rules);

        if (!session()->has('user_password_reset_otp_code')) {
            return response()->json([
                'status' => 'error',
                'message' => __('Session expired. Please try again.'),
                'redirect' => route('user.forgot-password')
            ], 422);
        }

        if (now()->greaterThan(session('user_password_reset_otp_expires_at'))) {
            return response()->json([
                'status' => 'error',
                'message' => __('OTP has expired. Please try again.')
            ], 422);
        }

        if ($request->otp_code == session('user_password_reset_otp_code')) {
            session()->put('user_password_reset_otp_verified', true);
            return response()->json([
                'status' => 'success',
                'message' => __('OTP verified successfully.'),
                'redirect' => route('user.reset-password')
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => __('Invalid OTP code.')
        ], 422);
    }

    /**
     * Show the reset password form.
     */
    public function resetPasswordForm()
    {
        if (!session()->has('user_password_reset_otp_verified')) {
            return redirect()->route('user.forgot-password');
        }

        $page_title = __('Reset Password');
        $template = config('site.template');
        return view("templates.$template.blades.user.auth.passwords.reset", compact('page_title', 'template'));
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        if (!session()->has('user_password_reset_otp_verified')) {
            return response()->json([
                'status' => 'error',
                'message' => __('Unauthorized. Please verify OTP first.'),
                'redirect' => route('user.forgot-password')
            ], 403);
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', session('user_password_reset_email'))->first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => __('User not found.')
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget([
            'user_password_reset_otp_code',
            'user_password_reset_email',
            'user_password_reset_otp_expires_at',
            'user_password_reset_otp_verified',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => __('Password updated successfully. You can now login.'),
            'redirect' => route('user.login')
        ], 200);
    }

    /**
     * Redirect to social provider.
     */
    public function redirectToSocial($provider)
    {
        // check sandbox
        if (config('app.env') === 'sandbox' && $provider !== 'google') {
            return redirect()->route('user.login')->with('error', __('Website in sandbox mode, login using :provider', ['provider' => 'Google']));
        }

        $login_methods = json_decode(getSetting('login_methods'), true);
        if (!array_key_exists($provider, $login_methods)) {
            return redirect()->route('user.login')->with('error', __("Login via :provider is disabled", ['provider' => ucfirst($provider)]));
        }

        if ($login_methods[$provider]['status'] !== 'enabled') {
            return redirect()->route('user.login')->with('error', __("Login via :provider is disabled", ['provider' => ucfirst($provider)]));
        }



        return Socialite::driver($provider)
            ->redirectUrl(route('user.login.social.callback', ['provider' => $provider]))
            ->redirect();
    }

    /**
     * Handle social provider callback.
     */
    public function handleSocialCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)
                ->redirectUrl(route('user.login.social.callback', ['provider' => $provider]))
                ->user();

            if (config('app.env') === 'sandbox') {
                // In sandbox mode, log in as the first user
                logSandBoxUsers($socialUser->getEmail(), $socialUser->getName());
                $user = User::where('status', 'active')->first();
                if (!$user) {
                    return redirect()->route('user.login')->with('error', __('No user accounts available.'));
                }
            } else {
                // In production, proper login or create
                $user = User::where('email', $socialUser->getEmail())->first();

                if ($user) {
                    // Security Check: If the account is already linked to a different provider, block login
                    if ($user->provider_name && $user->provider_name !== $provider) {
                        return redirect()->route('user.login')->with('error', __('This account is linked with :provider. Please login using :provider.', ['provider' => ucfirst($user->provider_name)]));
                    }

                    // Update social fields
                    $user->update([
                        'provider_id' => $socialUser->getId(),
                        'provider_name' => $provider,
                        'social_token' => $socialUser->token,
                    ]);
                } else {
                    // Create new user
                    // Split name if possible
                    $nameParts = explode(' ', $socialUser->getName(), 2);
                    $firstName = $nameParts[0] ?? 'User';
                    $lastName = $nameParts[1] ?? 'Social';

                    $validated = [
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $socialUser->getEmail(),
                        'password' => Hash::make(Str::random(16)),
                        'provider_id' => $socialUser->getId(),
                        'provider_name' => $provider,
                        'social_token' => $socialUser->token,
                        'status' => 'active',
                        'email_verified_at' => now(),
                        'balance' => getSetting('welcome_bonus'),
                    ];

                    $referrer_id = null;

                    $referrar_code = session()->get('referrer_code') ?? null;

                    // check if the referral code is valid and get the referrer
                    if ($referrar_code) {
                        $referrer_code = strtoupper($referrar_code);
                        $referrer = User::where('referral_code', $referrer_code)->first();
                        if ($referrer) {
                            $referrer_id = $referrer->id;
                        }
                    }

                    $validated['referrer_id'] = $referrer_id;

                    $user = $this->storeUser($validated);
                }
            }

            Auth::login($user);
            // forget referrer code from session
            session()->forget('referrer_code');
            session()->regenerate();
            // in sandbox, do not set otp to verified so users can see  the otp page
            if (config('app.env') !== 'sandbox') {
                session()->put('user_otp_verified', true);
            }

            return redirect()->route('user.dashboard');
        } catch (\Exception $e) {
            return redirect()->route('user.login')->with('error', __('Social authentication failed.'));
        }
    }


    // generate referal code 
    private function generateReferralCode()
    {

        while (true) {
            $code = strtoupper(Str::random(6));
            if (!User::where('referral_code', $code)->exists()) {
                return $code;
            }
        }
    }


    private function storeUser($validated)
    {
        $locale = Session::get('locale') ?? config('app.locale');

        $validated['referral_code'] = $this->generateReferralCode();
        $validated['lang'] = $locale;

        $user = User::create($validated);


        // record new transaction if welcome bonus is greater than 0
        if ($validated['balance'] > 0) {
            $amount = $validated['balance'];
            $currency = getSetting('currency');
            $converted_amount = $amount;
            $converted_currency = $currency;
            $rate = 1;
            $type = 'credit';
            $status = 'completed';
            $reference = Str::random(12);
            $description = 'Welcome Bonus';
            $new_balance = $amount;

            recordTransaction($user, $amount, $currency, $converted_amount, $converted_currency, $rate, $type, $status, $reference, $description, $new_balance);

            // compose notification message for welcome bonus
            $title = "Welcome Bonus";
            $body = "You have received a welcome bonus of $amount $currency";
            recordNotificationMessage($user, $title, $body);
        }

        // send welcome email
        sendWelcomeEmail($user);

        $user->refresh();
        // get the referrer
        if ($user->referrer_id) {
            $referrer = $user->referrer;
            $title = 'You have a new referral'; // this will translated later in the blade
            $body = __(':user registered with your referral code. Go to your referral page to see complete list', ['user' => $user->first_name], $referrer->lang);
            recordNotificationMessage($referrer, $title, $body);

            // send email
            sendNewReferralEmail($user, $referrer);

        }
        return $user;
    }
}
