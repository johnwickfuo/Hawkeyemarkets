<?php

use App\Models\MenuItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->to_store() as $key => $value) {
            updateSetting($key, $value);
        }

        // Update some user menu items manaully
        $self_trading_menu_status = true;
        if (!moduleEnabled('forex_module') && !moduleEnabled('futures_module') && !moduleEnabled('margin_module')) {
            $self_trading_menu_status = false;
        }

        MenuItem::where('label', 'like', '%Self Trading%')
            ->update(['is_active' => $self_trading_menu_status]);


        $capital_instruments_menu_status = true;
        if (!moduleEnabled('stock_module') && !moduleEnabled('etf_module') && !moduleEnabled('bonds_module')) {
            $capital_instruments_menu_status = false;
        }

        MenuItem::where('label', 'like', '%Capital Instruments%')
            ->update(['is_active' => $capital_instruments_menu_status]);

        // Remove menu from cache
        cache()->forget('admin_menu_items');
        cache()->forget('user_menu_items');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }


    private function to_store()
    {
        return [
            'modules' => $this->getAvailableModules(),
        ];
    }

    private function getAvailableModules()
    {
        return [
            'investment_module' => [
                'name' => "Investment",
                'status' => "enabled",
                'menu_search' => [
                    [
                        'term' => 'investments',
                        'column' => 'label'
                    ]
                ],
                'description' => "Enables structured investment plans and managed portfolios. Administrators can create flexible investment packages while users invest, track performance, and earn returns based on plan terms.",
            ],

            'futures_module' => [
                'name' => "Crypto Futures Trading",
                'status' => "enabled",
                'menu_search' => [
                    [
                        'term' => 'Futures Trading',
                        'column' => 'label'
                    ],
                ],
                'description' => "Provides advanced cryptocurrency futures trading with leveraged long and short positions. Users can actively trade market volatility with real-time execution and risk management tools.",
            ],

            'forex_module' => [
                'name' => "Forex Trading",
                'status' => "enabled",
                'menu_search' => [
                    [
                        'term' => 'Forex Trading',
                        'column' => 'label',
                    ],
                ],
                'description' => "Unlocks access to global foreign exchange markets, allowing users to trade major, minor, and exotic currency pairs with professional-grade trading tools and analytics.",
            ],

            'margin_module' => [
                'name' => "Crypto Margin Trading",
                'status' => "enabled",
                'menu_search' => [
                    [
                        'term' => 'Margin Trading',
                        'column' => 'label'
                    ]
                ],
                'description' => "Allows users to trade cryptocurrencies using margin, increasing market exposure through leveraged positions while maintaining integrated risk control mechanisms.",
            ],

            'stock_module' => [
                'name' => "Stock Trading",
                'status' => "enabled",
                'menu_search' => [
                    [
                        'term' => 'Stocks',
                        'column' => 'label'
                    ],

                ],
                'description' => "Enables buying, selling, and portfolio tracking of publicly listed equities. Users can review company disclosures, monitor performance, and manage diversified stock investments.",
            ],

            'etf_module' => [
                'name' => "ETF Trading",
                'status' => "enabled",
                'menu_search' => [
                    [
                        'term' => 'ETFs',
                        'column' => 'label',
                    ]
                ],
                'description' => "Supports trading of Exchange-Traded Funds (ETFs), allowing users to invest in diversified asset baskets across sectors, indices, and global markets.",
            ],

            'bonds_module' => [
                'name' => "Bonds Trading",
                'status' => "enabled",
                'menu_search' => [
                    [
                        'term' => "Bonds",
                        'column' => 'label'
                    ]
                ],
                'description' => "Provides access to government and corporate bond instruments, enabling users to invest in fixed-income assets designed for stability and predictable returns.",
            ],

            'loan_module' => [
                'name' => "Loan",
                'status' => "disabled",
                'menu_search' => [
                    [
                        'term' => 'Loan',
                        'column' => 'label'
                    ],

                ],
                'description' => "Facilitates digital lending services where users can apply for loans, undergo approval processes, and manage repayment schedules directly within the platform.",
            ],

            'trading_bot_module' => [
                'name' => "Trading Bot",
                'status' => "disabled",
                'menu_search' => [
                    [
                        'term' => 'Trading Bots',
                        'column' => 'label'
                    ]
                ],
                'description' => "Allows automated trading through administrator-managed or strategy-based bots. Users can deploy bots to execute trades continuously based on predefined market strategies.",
            ],

            'p2p_transfer_module' => [
                'name' => "P2P Transfer",
                'status' => "disabled",
                'menu_search' => [
                    [
                        'term' => 'P2P Transfer',
                        'column' => 'label'
                    ]
                ],
                'description' => "Enables instant peer-to-peer fund transfers between platform users with secure internal settlement and transaction tracking.",
            ],
            'file_manager_module' => [
                'name' => "File Manager",
                'status' => "enabled",
                'menu_search' => [
                    [
                        'term' => 'File Manager',
                        'column' => 'label'
                    ]
                ],
                'description' => "Enables secure server-side file management for administrators, including file upload, code editing, archiving, extraction, and directory management within the platform environment.",
            ],
            'kyc_module' => [
                'name' => "KYC",
                'status' => "enabled",
                'menu_search' => [
                    [
                        'term' => 'KYC',
                        'column' => 'label'
                    ]
                ],
                'description' => "Provides identity verification workflows for user onboarding, document review, and compliance management.",
            ],

        ];
    }
};
