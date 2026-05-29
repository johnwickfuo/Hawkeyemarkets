<?php

// Define website defaults

return [
    // Will be placed in .env later with corresponding keys
    'template' => env("TEMPLATE", 'bento'),
    'app_name' => env("APP_NAME", 'lozand'),
    'favicon' => env("FAVICON", 'favicon.png'),
    'app_timezone' => env("APP_TIMEZONE", 'Europe/London'),
    'decimal_places' => env("DECIMAL_PLACES", 2),
    'use_vite' => env("USE_VITE", true),
    'search_engine_indexing' => env("SEARCH_ENGINE_INDEXING", true),
    'append_date_to_emails' => env("APPEND_DATE_TO_EMAILS", 'enabled'),
    'nowpayment_api_key' => env("NOWPAYMENT_API_KEY", ''),
    'nowpayment_secret_key' => env("NOWPAYMENT_SECRET_KEY", ''),
    'nowpayment_email' => env("NOWPAYMENT_EMAIL", ''),
    'nowpayment_password' => env("NOWPAYMENT_PASSWORD", ''),
    'nowpayment_2fa_secret' => env("NOWPAYMENT_2FA_SECRET", ''),
    'product_key' => env('PRODUCT_KEY'),
    'version' => env('APP_VERSION', '1.0.0'),

    'settings_defaults' => [
        // Core
        'name' => 'lozand',
        'logo_square' => 'logo-square.png',
        'logo_rectangle' => 'logo-rectangle.png',
        'favicon' => 'favicon.png',
        'email' => 'info@lozand.com',
        'timezone' => env("APP_TIMEZONE", 'Europe/London'),


        // Security
        'email_verification' => 'enabled',
        'google_recaptcha' => 'disabled',
        'require_strong_password' => 'disabled',
        'login_otp' => 'enabled',

        // Benefits
        'welcome_bonus' => 10,
        'referral_bonus' => [
            10,
            5,
            3,
            0,
            0,
            0,
        ],

        // System
        'pagination' => 10,
        'delete_notification_message' => 'enabled',
        //langauges

        // finance
        'currency' => 'GBP',
        'currency_symbol' => '£',
        'currency_symbol_position' => 'before', //or after
        'decimal_places' => 2,

        // Finance -  deposit
        'min_deposit' => 1,
        'max_deposit' => 6000,
        'deposit_fee' => 5,
        'deposit_expires_at' => 10, //hours

        // Finance -  withdrawal
        'min_withdrawal' => 1,
        'max_withdrawal' => 6000,
        'withdrawal_fee' => 5,

        // Finance - stocks
        'min_stock_purchase' => 250,
        'max_stock_purchase' => 10000,
        'stock_purchase_fee_percent' => 1.5,
        'stock_sale_fee_percent' => 1.5,

        // Finance - ETFs
        'min_etf_purchase' => 250,
        'max_etf_purchase' => 10000,
        'etf_purchase_fee_percent' => 1.5,
        'etf_sale_fee_percent' => 1.5,

        // finance - Bonds
        'min_bond_purchase' => 250,
        'max_bond_purchase' => 10000,
        'bond_purchase_fee_percent' => 1.5,
        'bond_sale_fee_percent' => 1.5,

        // Email Notification setting
        'email_notification' => [
            'append_date_to_subject' => 'enabled',
            'email_queue' => 'disabled',
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
            ],
        ],

        // regulatory compliance
        'regulatory_compliance' => [
            'regulators' => [
                'Financial Industry Regulatory Authority (FINRA)',
                'Securities and Exchange Commission (SEC)',
            ],
            'pdf_certificates' => [
                [
                    'name' => 'Broker-Dealer Registration Certificate',
                    'file' => 'broker_dealer_certificate.pdf',
                ],
                [
                    'name' => 'Investment Adviser (RIA) Certificate',
                    'file' => 'investment_adviser_certificate.pdf',
                ],
                [
                    'name' => 'Money Services Business (MSB) Certificate',
                    'file' => 'money_services_business_certificate.pdf',
                ],
                [
                    'name' => 'Money Transmitter License Certificate',
                    'file' => 'money_transmitter_license_certificate.pdf',
                ],
            ],
        ],

        // SEO
        'seo_description' => 'Invest in stocks, ETFs, and cryptocurrencies on a secure, data-driven financial platform designed for smart portfolio growth and long-term wealth management.',
        'seo_keywords' => 'investment platform, online investing, stocks trading, ETF investment, cryptocurrency investment, portfolio management, wealth management, financial platform',
        'social_title' => 'The Complete Financial Management Ecosystem',
        'social_description' => 'Build, manage, and grow your investment portfolio with advanced analytics, automated insights, and secure asset management across stocks, ETFs, and crypto.',
        'seo_image' => 'seo-banner.png',

        'social_media' => [
            'twitter' => null,
            'facebook' => null,
            'instagram' => null,
            'linkedin' => null,
            'youtube' => null,
            'telegram' => null,
            'whatsapp' => null,
            'tiktok' => null,
            'x' => null,
        ],

    ]
];