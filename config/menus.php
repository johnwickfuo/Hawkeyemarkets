<?php

function moduleEnabledFromJson($module_key)
{
    $modules_json_path = base_path('public/assets/json/modules.json');
    if (!file_exists($modules_json_path)) {
        return false;
    }
    $modules = json_decode(file_get_contents($modules_json_path), true) ?? [];
    $module = $modules[$module_key] ?? null;

    if (!$module) {
        return false;
    }

    return $module['status'] == 'enabled' ? true : false;
}


return [
    'header_nav' => [
        [
            'name' => 'Home',
            'route_name' => 'home',
            'link' => null,
            'type' => 'link', // link, dropdown, mega
            'is_external' => false,
            'is_active' => true
        ],
        [
            'name' => 'Investments',
            'route_name' => '#',
            'link' => '#',
            'type' => 'mega',
            'sections' => [
                [
                    'name' => 'Sectors',
                    'items' => [
                        [
                            'name' => 'Stocks & ETFs',
                            'route_name' => 'sectors.stocks-etfs',
                            'link' => null,
                            'is_external' => false,
                            'is_active' => true
                        ],
                        [
                            'name' => 'Crypto Assets',
                            'route_name' => 'sectors.crypto-assets',
                            'link' => null,
                            'is_external' => false,
                            'is_active' => true
                        ],
                        [
                            'name' => 'Real Estate',
                            'route_name' => 'sectors.real-estate',
                            'link' => null,
                            'is_external' => false,
                            'is_active' => true
                        ],
                        [
                            'name' => 'Fixed Income',
                            'route_name' => 'sectors.fixed-income',
                            'link' => null,
                            'is_external' => false,
                            'is_active' => true
                        ],
                        [
                            'name' => 'Commoditites',
                            'route_name' => 'sectors.commodities',
                            'link' => null,
                            'is_external' => false,
                            'is_active' => true
                        ],
                        [
                            'name' => 'Business & Startups',
                            'route_name' => 'sectors.businesses-startups',
                            'link' => null,
                            'is_external' => false,
                            'is_active' => true
                        ],
                        [
                            'name' => 'Arts & Collectibles',
                            'route_name' => 'sectors.art-collectibles',
                            'link' => null,
                            'is_external' => false,
                            'is_active' => true
                        ],
                        [
                            'name' => 'Gaming & Esports',
                            'route_name' => 'sectors.gaming-esports',
                            'link' => null,
                            'is_external' => false,
                            'is_active' => true
                        ],
                        [
                            'name' => 'Cash & Savings',
                            'route_name' => 'sectors.cash-savings',
                            'link' => null,
                            'is_external' => false,
                            'is_active' => true
                        ],
                    ]
                ],
                [
                    'name' => 'Capital Instruments',
                    'items' => [
                        [
                            'name' => 'Stocks',
                            'route_name' => 'capital-instruments.stocks',
                            'link' => null,
                            'is_external' => false,
                            'is_active' => moduleEnabledFromJson('stock_module')
                        ],
                        [
                            'name' => 'Bonds',
                            'route_name' => 'capital-instruments.bonds',
                            'link' => null,
                            'is_external' => false,
                            'is_active' => moduleEnabledFromJson('bonds_module')
                        ],
                        [
                            'name' => 'Mutual Funds',
                            'route_name' => 'capital-instruments.mutual-funds',
                            'link' => null,
                            'is_external' => false,
                            'is_active' => moduleEnabledFromJson('mutual_fund_module')
                        ],
                        [
                            'name' => 'ETFs',
                            'route_name' => 'capital-instruments.etfs',
                            'link' => null,
                            'is_external' => false,
                            'is_active' => moduleEnabledFromJson('etf_module')
                        ]
                    ],
                    'is_active' => moduleEnabledFromJson('stock') || moduleEnabledFromJson('bonds_module') || moduleEnabledFromJson('mutual_fund_module') || moduleEnabledFromJson('etf_module')
                ],
                [
                    'name' => 'Managed Portfolios',
                    'items' => [
                        [
                            'name' => 'Investment Plans',
                            'route_name' => 'investment-plans',
                            'link' => null,
                            'is_external' => false,
                            'is_active' => moduleEnabledFromJson('investment_module')
                        ]
                    ],
                    'is_active' => moduleEnabledFromJson('investment_module')
                ]
            ],
            'is_external' => false,
            'is_active' => true,
        ],
        [
            'name' => 'Self Trading',
            'route_name' => '#',
            'link' => '#',
            'type' => 'dropdown',
            'sub_menu' => [
                [
                    'name' => "Futures Trading",
                    'route_name' => 'trading.futures',
                    'link' => null,
                    'is_external' => false,
                    'is_active' => moduleEnabledFromJson('futures_module')
                ],
                [
                    'name' => 'Margin Trading',
                    'route_name' => 'trading.margin',
                    'link' => null,
                    'is_external' => false,
                    'is_active' => moduleEnabledFromJson('margin_module')
                ],
                [
                    'name' => 'Forex Trading',
                    'route_name' => 'trading.forex',
                    'link' => null,
                    'is_external' => false,
                    'is_active' => moduleEnabledFromJson('forex_module')
                ],
                // [
                //     'name' => 'Commodity Trading',
                //     'route_name' => 'trading.commodity',
                //     'link' => null,
                //     'is_external' => false,
                //     'is_active' => true
                // ],
            ],
            'is_external' => false,
            'is_active' => moduleEnabledFromJson('futures_module') || moduleEnabledFromJson('margin_module') || moduleEnabledFromJson('forex_module')
        ],
        [
            'name' => 'Company',
            'route_name' => null,
            'link' => '#',
            'type' => 'dropdown',
            'sub_menu' => [
                [
                    'name' => 'About',
                    'route_name' => 'about',
                    'link' => null,
                    'is_external' => false,
                    'is_active' => true
                ],
                [
                    'name' => 'License & Regulation',
                    'route_name' => 'license',
                    'link' => null,
                    'is_external' => false,
                    'is_active' => true
                ],
                [
                    'name' => 'Contact',
                    'route_name' => 'contact',
                    'link' => null,
                    'is_external' => false,
                    'is_active' => true
                ],
            ],
            'is_external' => false,
            'is_active' => true
        ],
    ],

    'footer_nav' => [
        [
            'name' => 'Ecosystem',
            'items' => [
                ['name' => 'Stocks & ETFs', 'route_name' => 'sectors.stocks-etfs', 'is_active' => true],
                ['name' => 'Crypto Assets', 'route_name' => 'sectors.crypto-assets', 'is_active' => true],
                ['name' => 'Real Estate', 'route_name' => 'sectors.real-estate', 'is_active' => true],
                // ['name' => 'Commodities', 'route_name' => 'sectors.commodities', 'is_active' => true],
            ],
            'is_active' => moduleEnabledFromJson('investment_module')
        ],
        [
            'name' => 'Instruments',
            'items' => [
                ['name' => 'Stocks', 'route_name' => 'capital-instruments.stocks', 'is_active' => moduleEnabledFromJson('stock_module')],
                ['name' => 'Bonds', 'route_name' => 'capital-instruments.bonds', 'is_active' => moduleEnabledFromJson('bonds_module')],
                ['name' => 'Mutual Funds', 'route_name' => 'capital-instruments.mutual-funds', 'is_active' => moduleEnabledFromJson('mutual_fund_module')],
                ['name' => 'ETFs', 'route_name' => 'capital-instruments.etfs', 'is_active' => moduleEnabledFromJson('etf_module')],
            ],
            'is_active' => moduleEnabledFromJson('stock_module') || moduleEnabledFromJson('bonds_module') || moduleEnabledFromJson('mutual_fund_module') || moduleEnabledFromJson('etf_module')
        ],
        [
            'name' => 'Trading',
            'items' => [
                ['name' => 'Investment Plans', 'route_name' => 'investment-plans', 'is_active' => moduleEnabledFromJson('investment_module')],
                ['name' => 'Futures Trading', 'route_name' => 'trading.futures', 'is_active' => moduleEnabledFromJson('futures_module')],
                ['name' => 'Margin Trading', 'route_name' => 'trading.margin', 'is_active' => moduleEnabledFromJson('margin_module')],
                ['name' => 'Forex Trading', 'route_name' => 'trading.forex', 'is_active' => moduleEnabledFromJson('forex_module')],
            ],
            'is_active' => moduleEnabledFromJson('investment_module') || moduleEnabledFromJson('futures_module') || moduleEnabledFromJson('margin_module') || moduleEnabledFromJson('forex_module')
        ],
        [
            'name' => 'Governance',
            'items' => [
                ['name' => 'About Us', 'route_name' => 'about', 'is_active' => true],
                ['name' => 'Contact', 'route_name' => 'contact', 'is_active' => true],
                ['name' => 'License & Regulation', 'route_name' => 'license', 'is_active' => true],
                ['name' => 'Privacy Policy', 'route_name' => 'privacy-policy', 'is_active' => true],
                ['name' => 'Terms of Service', 'route_name' => 'terms-and-conditions', 'is_active' => true],
                ['name' => 'Risk Disclosure', 'route_name' => 'risk-disclosure', 'is_active' => true],
            ],
            'is_active' => true
        ],
    ]

];