<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    //register page index
    public function index()
    {
        $page_title = __('Register');
        $template = config('site.template');

        // check if it has refferal code in the request
        if (request()->has('ref')) {
            $referrer_code = strtoupper(request()->ref);
            session()->put('referrer_code', $referrer_code);
        }

        $throttle = false;
        if (session('register_info')) {
            $otp_id = session('register_info')['otp_id'];
            if (cache()->has('resend_throttle_' . $otp_id)) {
                $throttle = true;
            }
        }

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

        return view("templates.$template.blades.user.auth.register", compact('page_title', 'throttle', 'login_methods', 'only_email'));
    }


    // validate registration
    public function registerValidate(Request $request)
    {
        //in sandbox only login using google
        if (config('app.env') == 'sandbox') {
            return response()->json([
                'status' => 'error',
                'message' => __('Registration with email is disabled in sandbox mode. Please register with Google.'),

            ], 401);
        }
        $google_recaptcha = getSetting('google_recaptcha');

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => validPassword(),
            'referral_code' => 'nullable|string|max:255',
        ];

        if ($google_recaptcha === 'enabled') {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $validated = $request->validate($rules);

        $referrer_id = null;

        $referrar_code = session()->get('referrer_code') ?? $request->referral_code ?? null;

        // check if the referral code is valid and get the referrer
        if ($referrar_code) {
            $referrer_code = strtoupper($referrar_code);
            $referrer = User::where('referral_code', $referrer_code)->first();
            if ($referrer) {
                $referrer_id = $referrer->id;
            }
        }

        $validated['referrer_id'] = $referrer_id;
        $validated['balance'] = getSetting('welcome_bonus');

        // generate unique id for this registration attempt from the email address
        $validated['otp_id'] = str_replace(['.', '@', '+'], '_', $validated['email']);

        session()->put('register_info', $validated);

        // register the user email verification is not required
        if (getSetting('email_verification') === 'disabled') {
            $this->storeUser($validated);
            return response()->json([
                'status' => 'success',
                'message' => __('Registration successful')
            ]);
        }


        // generate otp code, numeric 6 digit
        $otp_code = mt_rand(100000, 999999);

        // store the otp code in cache for 15 minutes
        cache()->put('otp_' . $validated['otp_id'], $otp_code, now()->addMinutes(15));

        // send verification email
        sendVerificationEmail($validated['first_name'], $validated['email'], $otp_code);

        // set throttle for 60 seconds for initial send
        cache()->put('resend_throttle_' . $validated['otp_id'], true, now()->addSeconds(60));

        return response()->json([
            'status' => 'success',
            'message' => __('Registration successful, please verify your email')
        ]);


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


    // store the user
    private function storeUser($validated)
    {
        $locale = Session::get('locale') ?? config('app.locale');
        $user = new User();
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->referral_code = $this->generateReferralCode();
        $user->referrer_id = $validated['referrer_id'];
        $user->balance = $validated['balance'];
        $user->lang = $locale;
        $user->save();


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


        // remove validated data from session
        session()->forget('register_info');

        // forget referral code from session
        session()->forget('referrer_code');

        // automatically log the user in
        Auth::login($user);

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


    // Email verification
    public function emailVerification(Request $request)
    {
        $rules = [
            'email' => 'required|email|max:255',
            'otp_code' => 'required|digits:6',
        ];

        $validated = $request->validate($rules);

        $otp_id = str_replace(['.', '@', '+'], '_', $validated['email']);

        $otp_code = cache()->get('otp_' . $otp_id);

        if (!$otp_code) {
            return response()->json(['status' => 'error', 'message' => __('Invalid or expired OTP code')]);
        }

        // cast the validated otp to int
        $validated['otp_code'] = (int) $validated['otp_code'];
        if ($otp_code !== $validated['otp_code']) {

            return response()->json(['status' => 'error', 'message' => __('Invalid OTP code')]);
        }

        $register_info = session()->get('register_info');

        $user = $this->storeUser($register_info);

        // update the email_verified_at
        $user->email_verified_at = now();
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => __('Registration successful'),
            'redirect' => url('user/dashboard')
        ]);
    }

    // Resend verification code
    public function resendVerification(Request $request)
    {
        $register_info = session()->get('register_info');

        if (!$register_info) {
            return response()->json([
                'status' => 'error',
                'message' => __('Session expired, please register again'),
                'action' => 'redirect',
                'redirect_to' => route('user.register')
            ]);
        }

        // Throttle check (e.g., 1 minute)
        if (cache()->has('resend_throttle_' . $register_info['otp_id'])) {
            return response()->json([
                'status' => 'error',
                'message' => __('Please wait before requesting a new code')
            ]);
        }

        // generate otp code, numeric 6 digit
        $otp_code = mt_rand(100000, 999999);

        // store the otp code in cache for 15 minutes
        cache()->put('otp_' . $register_info['otp_id'], $otp_code, now()->addMinutes(15));

        // set throttle for 60 seconds
        cache()->put('resend_throttle_' . $register_info['otp_id'], true, now()->addSeconds(60));

        // send verification email 
        sendVerificationEmail($register_info['first_name'], $register_info['email'], $otp_code);

        return response()->json([
            'status' => 'success',
            'message' => __('Verification code resent successfully.')
        ]);
    }

    // Cancel registration and clear session
    public function registerCancel()
    {
        session()->forget('register_info');

        return response()->json([
            'status' => 'success',
            'message' => __('Registration cancelled'),
            'action' => 'redirect',
            'redirect_to' => route('user.register')
        ]);
    }

}
