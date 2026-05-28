<?php

return [

    'stocks_and_etfs' => [
        'context' => 'Investments in publicly traded companies and exchange-traded funds that track markets, sectors, or themes.',
        'risk_level' => 'medium',
        'risk_profile' => 'balanced',
        'investment_goal' => 'long_term',
        'volatility' => 'medium',
        'how_it_works' => [
            'Exposure to global stock markets and indices.',
            'Earnings generated from price appreciation and dividends.',
            'Returns credited based on selected investment plans.',
            'Capital returned at maturity depending on plan structure.',
        ],
        'psychology' => [
            'Long-term wealth building.',
            'Broad market exposure.',
            'Portfolio diversification.',
        ],
        'ideal_for' => 'Investors seeking steady growth with moderate risk.',
        'not_ideal' => 'Those looking for guaranteed or short-term returns.',
        'metrics' => [
            'total_invested' => null,
            'earnings_generated' => null,
            'active_investors' => null,
        ],
        'earnings_generated_from' => [
            'price_appreciation' => 'Increase in share and ETF prices over time.',
            'dividends' => 'Periodic dividend distributions from profitable companies.',
            'index_growth' => 'Market-wide growth reflected in index-tracking ETFs.',
            'sector_rotation' => 'Capital gains from shifting exposure between sectors.',
        ],
    ],

    'crypto_assets' => [
        'context' => 'Digital assets traded on blockchain networks, offering high growth potential with higher volatility.',
        'risk_level' => 'high',
        'risk_profile' => 'growth',
        'investment_goal' => 'long_term',
        'volatility' => 'high',
        'how_it_works' => [
            'Trades across major global exchanges.',
            'Earnings generated from price movement and liquidity strategies.',
            'Returns credited periodically based on selected plans.',
            'Capital returned at maturity depending on plan.',
        ],
        'psychology' => [
            'Potential for higher returns.',
            'Exposure to emerging technology.',
            'Portfolio diversification.',
        ],
        'ideal_for' => 'Growth-focused investors with higher risk tolerance.',
        'not_ideal' => 'Capital preservation or short-term liquidity needs.',
        'metrics' => [
            'total_invested' => null,
            'earnings_generated' => null,
            'active_investors' => null,
        ],
        'earnings_generated_from' => [
            'price_appreciation' => 'Capital gains from market price movements.',
            'staking' => 'Rewards for securing blockchain networks.',
            'yield_farming' => 'Liquidity incentives from DeFi protocols.',
            'arbitrage' => 'Price inefficiencies across exchanges.',
            'lending' => 'Interest earned from crypto lending.',
            'trading' => 'Short-term market execution strategies.',
        ],
    ],

    'real_estate' => [
        'context' => 'Property-backed investments providing rental income and long-term capital appreciation.',
        'risk_level' => 'medium',
        'risk_profile' => 'balanced',
        'investment_goal' => 'long_term',
        'volatility' => 'low',
        'how_it_works' => [
            'Capital allocated to residential and commercial properties.',
            'Rental income generated from tenants.',
            'Returns credited periodically based on rental performance.',
            'Capital returned at property exit or plan maturity.',
        ],
        'psychology' => [
            'Stable income generation.',
            'Tangible asset ownership.',
            'Inflation protection.',
        ],
        'ideal_for' => 'Investors seeking steady income and long-term stability.',
        'not_ideal' => 'Those seeking rapid or short-term gains.',
        'metrics' => [
            'total_invested' => null,
            'earnings_generated' => null,
            'active_investors' => null,
        ],
        'earnings_generated_from' => [
            'rental_income' => 'Monthly rental payments from tenants.',
            'property_appreciation' => 'Increase in property market value.',
            'lease_settlements' => 'Commercial lease agreements.',
        ],
    ],

    'fixed_income' => [
        'context' => 'Low-risk instruments that provide predictable interest-based returns.',
        'risk_level' => 'low',
        'risk_profile' => 'conservative',
        'investment_goal' => 'short_term',
        'volatility' => 'low',
        'how_it_works' => [
            'Capital invested in bonds and debt instruments.',
            'Interest accrues at predefined rates.',
            'Returns credited on a fixed schedule.',
            'Principal returned at maturity.',
        ],
        'psychology' => [
            'Capital preservation.',
            'Predictable income.',
            'Low volatility exposure.',
        ],
        'ideal_for' => 'Risk-averse investors and income-focused users.',
        'not_ideal' => 'Those seeking high growth.',
        'metrics' => [
            'total_invested' => null,
            'earnings_generated' => null,
            'active_investors' => null,
        ],
        'earnings_generated_from' => [
            'interest_accrual' => 'Scheduled interest payments.',
            'bond_coupons' => 'Periodic coupon distributions.',
            'treasury_yields' => 'Government-backed yield instruments.',
        ],
    ],

    'commodities' => [
        'context' => 'Investments in physical goods such as gold, energy, and agricultural products.',
        'risk_level' => 'medium',
        'risk_profile' => 'balanced',
        'investment_goal' => 'medium_term',
        'volatility' => 'medium',
        'how_it_works' => [
            'Exposure to commodity price movements.',
            'Returns driven by global supply and demand.',
            'Earnings credited based on market performance.',
            'Capital returned at plan maturity.',
        ],
        'psychology' => [
            'Inflation hedging.',
            'Portfolio diversification.',
            'Global macro exposure.',
        ],
        'ideal_for' => 'Investors seeking diversification and inflation protection.',
        'not_ideal' => 'Those uncomfortable with market-driven pricing.',
        'metrics' => [
            'total_invested' => null,
            'earnings_generated' => null,
            'active_investors' => null,
        ],
        'earnings_generated_from' => [
            'price_movements' => 'Changes in commodity market prices.',
            'inflation_hedge' => 'Value preservation during inflation.',
            'supply_demand_shifts' => 'Global production and consumption trends.',
        ],
    ],

    'businesses_and_startups' => [
        'context' => 'Private investments in early-stage and growth-stage businesses.',
        'risk_level' => 'high',
        'risk_profile' => 'growth',
        'investment_goal' => 'long_term',
        'volatility' => 'high',
        'how_it_works' => [
            'Capital deployed into private businesses.',
            'Returns generated from business growth and profitability.',
            'Earnings credited based on milestones or exits.',
            'Capital returned upon exit or maturity.',
        ],
        'psychology' => [
            'High return potential.',
            'Participation in innovation.',
            'Long-term value creation.',
        ],
        'ideal_for' => 'Investors willing to accept higher risk for higher reward.',
        'not_ideal' => 'Short-term or risk-averse investors.',
        'metrics' => [
            'total_invested' => null,
            'earnings_generated' => null,
            'active_investors' => null,
        ],
        'earnings_generated_from' => [
            'valuation_growth' => 'Increase in company valuation.',
            'profit_sharing' => 'Revenue or profit participation.',
            'exit_events' => 'Acquisitions or public listings.',
        ],
    ],

    'art_and_collectibles' => [
        'context' => 'Alternative investments in fine art, rare assets, and collectibles.',
        'risk_level' => 'medium',
        'risk_profile' => 'balanced',
        'investment_goal' => 'long_term',
        'volatility' => 'low',
        'how_it_works' => [
            'Fractional ownership of curated assets.',
            'Value driven by rarity and market demand.',
            'Returns realized through appreciation.',
            'Capital returned upon asset sale or maturity.',
        ],
        'psychology' => [
            'Non-correlated asset exposure.',
            'Cultural and rarity value.',
            'Long-term appreciation.',
        ],
        'ideal_for' => 'Investors interested in alternative, non-market assets.',
        'not_ideal' => 'Those seeking frequent liquidity.',
        'metrics' => [
            'total_invested' => null,
            'earnings_generated' => null,
            'active_investors' => null,
        ],
        'earnings_generated_from' => [
            'market_appreciation' => 'Increase in asset valuation.',
            'rarity_premium' => 'Scarcity-driven value growth.',
            'secondary_sales' => 'Profits from asset resale.',
        ],
    ],

    'cash_and_savings' => [
        'context' => 'Low-risk cash holdings designed for liquidity and capital preservation.',
        'risk_level' => 'low',
        'risk_profile' => 'conservative',
        'investment_goal' => 'short_term',
        'volatility' => 'low',
        'how_it_works' => [
            'Funds allocated to secure cash-equivalent instruments.',
            'Interest accrues over short periods.',
            'Returns credited regularly.',
            'Funds remain highly liquid.',
        ],
        'psychology' => [
            'Safety and liquidity.',
            'Capital preservation.',
            'Short-term flexibility.',
        ],
        'ideal_for' => 'Users prioritizing liquidity and minimal risk.',
        'not_ideal' => 'High-growth seekers.',
        'metrics' => [
            'total_invested' => null,
            'earnings_generated' => null,
            'active_investors' => null,
        ],
        'earnings_generated_from' => [
            'interest_accrual' => 'Interest on cash balances.',
            'liquidity_deployment' => 'Short-term liquidity optimization.',
            'savings_yield' => 'Scheduled savings returns.',
        ],
    ],

    'gaming_and_esports' => [
        'context' => 'Investments focused on the gaming and esports ecosystem, including competitive teams, gaming platforms, digital assets, and interactive entertainment technologies.',
        'risk_level' => 'high',
        'risk_profile' => 'growth',
        'investment_goal' => 'medium_term',
        'volatility' => 'high',
        'how_it_works' => [
            'Capital allocated to esports teams, gaming platforms, and related digital economies.',
            'Earnings generated from sponsorships, tournament revenues, platform growth, and digital asset performance.',
            'Returns credited periodically based on selected investment plans.',
            'Capital returned at maturity depending on plan structure.',
        ],
        'psychology' => [
            'Exposure to a fast-growing digital entertainment industry.',
            'Participation in emerging digital economies.',
            'High growth potential driven by global adoption.',
        ],
        'ideal_for' => 'Investors seeking exposure to emerging digital entertainment and esports markets.',
        'not_ideal' => 'Risk-averse investors or those seeking predictable income.',
        'metrics' => [
            'total_invested' => null, // to be replaced from DB
            'earnings_generated' => null, // to be replaced from DB
            'active_investors' => null, // to be replaced from DB
        ],
        'earnings_generated_from' => [
            'tournament_revenue' => 'Revenue generated from competitive gaming tournaments and leagues.',
            'sponsorship_income' => 'Brand sponsorships and advertising partnerships.',
            'platform_growth' => 'Value increase from expanding gaming platforms and user bases.',
            'digital_asset_utilization' => 'Performance of in-game assets and digital items.',
            'ecosystem_expansion' => 'Growth of gaming and esports ecosystems globally.',
        ],
    ],


];
