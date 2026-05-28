<?php

$risk_profiles = ['conservative', 'balanced', 'growth'];
$investment_goals = ['short_term', 'medium_term', 'long_term'];

return [

    'stocks_and_etfs' => [
        'name' => 'Stocks & ETFs',
        'description' => 'Invest in publicly traded companies and exchange-traded funds that track markets, sectors, or themes.',
        'recommended_for' => 'Users seeking long-term growth, market exposure, and portfolio diversification.',
        'risk_profile' => 'balanced',
        'investment_goal' => 'long_term',
    ],

    'crypto_assets' => [
        'name' => 'Crypto Assets',
        'description' => 'Digital assets including cryptocurrencies, blockchain tokens, and decentralized finance opportunities.',
        'recommended_for' => 'Users comfortable with high volatility and looking for aggressive growth potential.',
        'risk_profile' => 'growth',
        'investment_goal' => 'medium_term',
    ],

    'real_estate' => [
        'name' => 'Real Estate',
        'description' => 'Invest in property-backed assets, rental income opportunities, and real estate investment vehicles.',
        'recommended_for' => 'Users seeking stable income and long-term capital appreciation.',
        'risk_profile' => 'balanced',
        'investment_goal' => 'long_term',
    ],

    'fixed_income' => [
        'name' => 'Fixed Income',
        'description' => 'Low-risk investments such as bonds and interest-based products that provide predictable returns.',
        'recommended_for' => 'Users focused on capital preservation and consistent income.',
        'risk_profile' => 'conservative',
        'investment_goal' => 'short_term',
    ],

    'commodities' => [
        'name' => 'Commodities',
        'description' => 'Exposure to physical assets like gold, oil, agricultural products, and raw materials.',
        'recommended_for' => 'Users looking to hedge against inflation and diversify their portfolio.',
        'risk_profile' => 'balanced',
        'investment_goal' => 'medium_term',
    ],

    'businesses_and_startups' => [
        'name' => 'Businesses & Startups',
        'description' => 'Private investments in early-stage companies and growing businesses.',
        'recommended_for' => 'Users willing to take higher risks in exchange for potential high returns.',
        'risk_profile' => 'growth',
        'investment_goal' => 'long_term',
    ],

    'art_and_collectibles' => [
        'name' => 'Art & Collectibles',
        'description' => 'Alternative investments such as fine art, rare items, and digital collectibles.',
        'recommended_for' => 'Users interested in non-traditional assets and long-term value appreciation.',
        'risk_profile' => 'balanced',
        'investment_goal' => 'long_term',
    ],

    'gaming_and_esports' => [
        'name' => 'Gaming & Esports',
        'description' => 'Invest in the gaming industry, esports teams, platforms, and related digital economies.',
        'recommended_for' => 'Users looking for exposure to fast-growing digital entertainment markets.',
        'risk_profile' => 'growth',
        'investment_goal' => 'medium_term',
    ],

    'cash_and_savings' => [
        'name' => 'Cash & Savings',
        'description' => 'Low-risk holdings such as savings accounts and cash-based instruments for liquidity.',
        'recommended_for' => 'Users prioritizing safety, liquidity, and short-term financial needs.',
        'risk_profile' => 'conservative',
        'investment_goal' => 'short_term',
    ],
];

