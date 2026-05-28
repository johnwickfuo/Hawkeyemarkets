<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    // index
    public function index()
    {
        $page_title = __('Email Settings');
        $template = config('site.template');

        $mail_config = [
            'driver' => env('MAIL_MAILER', 'smtp'),
            'host' => env('MAIL_HOST'),
            'port' => env('MAIL_PORT'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'encryption' => env('MAIL_ENCRYPTION'),
            'from_address' => env('MAIL_FROM_ADDRESS'),
            'from_name' => env('MAIL_FROM_NAME'),
        ];

        $notifications = json_decode(getSetting('email_notification', '[]'), true);

        // Seed default structure if it doesn't exist (User requirement: cannot be empty)
        if (empty($notifications) || !isset($notifications['notifications'])) {
            $notifications = [
                'notifications' => [
                    'deposit' => [
                        'status' => 'enabled',
                        'tip' => 'If this is enabled, users will get email notification when they make deposits or when their deposit status changes.',
                        'warning' => null,
                    ],
                    'email_verification' => [
                        'status' => 'enabled',
                        'tip' => 'If this is enabled, users will get email notification when they register.',
                        'warning' => "If you have enabled email verification in the security setting and disabled this notification, users won't be able to sign up as no verification link or code will be sent.",
                    ],
                    'investment' => [
                        'status' => 'enabled',
                        'tip' => 'If this is enabled, users will get email notification when they make investments or when their investment status changes',
                        'warning' => null,
                    ],
                    'kyc' => [
                        'status' => 'enabled',
                        'tip' => "If this is enabled, users will get email notification when they carry out KYC verification or when their KYC status changes.",
                        'warning' => null,
                    ],
                    'otp_verification' => [
                        'status' => 'enabled',
                        'tip' => "If this is enabled, users will get email notification when they make attempt any action that requires an OTP code.",
                        'warning' => "If you have enabled OTP verification in the security setting and disabled this notification, users won't be able to login or carryout any action that requires otp verification as no OTP code will be sent.",
                    ],
                    'referral' => [
                        'status' => 'enabled',
                        'tip' => "If this is enabled, users will get email notification when someone sign up with their referral link or code.",
                        'warning' => null,
                    ],
                    'transaction' => [
                        'status' => 'enabled',
                        'tip' => "If this is enabled, users will get email notification when any transaction occurs on their account.",
                        'warning' => "Sending too many emails can trigger email quota limit, blacklisting or spam. Consult your hosting provider.",
                    ],
                    'welcome' => [
                        'status' => 'enabled',
                        'tip' => "If this is enabled, users will get email notification when their sign up is completed.",
                        'warning' => null,
                    ],
                    'stock' => [
                        'status' => 'enabled',
                        'tip' => "If this is enabled, users will get email notification when they purchase or sell stock.",
                        'warning' => null,
                    ],
                    'etf' => [
                        'status' => 'enabled',
                        'tip' => "If this is enabled, users will get email notification when they purchase or sell ETF.",
                        'warning' => null,
                    ],
                    'withdrawal' => [
                        'status' => 'enabled',
                        'tip' => "If this is enabled, users will get email notification when they withdraw or when their withdrawal status changes.",
                        'warning' => null,
                    ],
                    'account_ban' => [
                        'status' => 'enabled',
                        'tip' => "If this is enabled, users will get email notification when their account is banned or unbanned.",
                        'warning' => null,
                    ],
                ]
            ];
            updateSetting('email_notification', $notifications);
        }

        $email_queue = getSetting('email_queue', 'disabled');
        $append_date = config('site.append_date_to_emails');

        $email_templates = \File::allFiles(resource_path('views/templates/' . $template . '/mail'));
        $email_templates = collect($email_templates)->map(function ($file) {
            return [
                'name' => $file->getFilenameWithoutExtension(),
                'path' => $file->getPathname(),
            ];
        });

        return view("templates.$template.blades.admin.settings.email", compact(
            'page_title',
            'mail_config',
            'notifications',
            'email_queue',
            'append_date',
            'email_templates'
        ));
    }

    // update
    public function update(Request $request)
    {
        $request->validate([
            'mail_driver' => 'required|string|max:20',
            'mail_host' => 'required|string|max:191',
            'mail_port' => 'required|integer',
            'mail_username' => 'nullable|string|max:191',
            'mail_password' => 'nullable|string|max:191',
            'mail_encryption' => 'nullable|string|max:20',
            'mail_from_address' => 'required|email|max:191',
            'mail_from_name' => 'required|string|max:191',
            'notifications' => 'nullable|array',
        ]);

        // Update Env
        updateEnv('MAIL_MAILER', $request->mail_driver);
        updateEnv('MAIL_HOST', $request->mail_host);
        updateEnv('MAIL_PORT', $request->mail_port);
        updateEnv('MAIL_USERNAME', $request->mail_username);
        updateEnv('MAIL_PASSWORD', $request->mail_password);
        updateEnv('MAIL_ENCRYPTION', $request->mail_encryption);
        updateEnv('MAIL_FROM_ADDRESS', $request->mail_from_address);
        updateEnv('MAIL_FROM_NAME', $request->mail_from_name);
        updateEnv('APPEND_DATE_TO_EMAILS', $request->append_date_to_emails ? 'enabled' : 'disabled');

        // Update Global Toggles
        updateSetting('email_queue', $request->email_queue ? 'enabled' : 'disabled');

        // Update Notifications Setting Dynamically
        $current_config = json_decode(getSetting('email_notification', '[]'), true);

        if (isset($current_config['notifications']) && is_array($current_config['notifications'])) {
            foreach ($current_config['notifications'] as $key => $data) {
                $current_config['notifications'][$key]['status'] = ($request->notifications && isset($request->notifications[$key])) ? 'enabled' : 'disabled';
            }
            updateSetting('email_notification', $current_config);
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Email settings updated and system synchronized.')
            ]);
        }

        return back()->with('success', __('Email settings updated and system synchronized.'));
    }

    // test
    public function test(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $name = env('APP_NAME');
            $email = $request->email;

            \Illuminate\Support\Facades\Mail::raw("This is a test email from $name to verify your SMTP configuration is working correctly.", function ($message) use ($email, $name) {
                $message->to($email)
                    ->subject("Test Email from $name");
            });

            return response()->json([
                'status' => 'success',
                'message' => __('Test email sent successfully to :email', ['email' => $email])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('Mail sending failed: ') . $e->getMessage()
            ], 500);
        }
    }
}
