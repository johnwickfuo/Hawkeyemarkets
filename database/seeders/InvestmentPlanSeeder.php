<?php

namespace Database\Seeders;

use App\Models\InvestmentPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvestmentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        function preferredInterval(int $duration, string $durationType): string
        {
            if ($durationType === 'days' || ($durationType === 'months' && $duration <= 3)) {
                return 'daily';
            }

            if ($durationType === 'months' && $duration <= 12) {
                return 'weekly';
            }

            if ($durationType === 'years') {
                return 'weekly';
            }

            return 'monthly';
        }
        $plans = [
            // Stocks & ETFs (Long Term, Balanced)
            [
                'name' => 'Global Tech Giants ETF',
                'description' => 'A diversified portfolio focusing on leading technology companies worldwide.',
                'interests' => ['stocks_and_etfs'],
                'risk_profile' => 'balanced',
                'investment_goal' => 'long_term',
                'duration' => 5,
                'duration_type' => 'years',
                'min_investment' => 500,
                'max_investment' => 50000,
                'return_percent' => 12.5,
                'return_interval' => 'yearly',

                'compounding' => true,
                'capital_returned' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'S&P 500 Index Tracker',
                'description' => 'Low-cost index fund tracking the S&P 500 performance.',
                'interests' => ['stocks_and_etfs'],
                'risk_profile' => 'balanced',
                'investment_goal' => 'long_term',
                'duration' => 3,
                'duration_type' => 'years',
                'min_investment' => 1000,
                'max_investment' => 100000,
                'return_percent' => 8.0,
                'return_interval' => 'yearly',

                'compounding' => true,
                'capital_returned' => true,
            ],
            [
                'name' => 'Emerging Markets Opportunities',
                'description' => 'Exposure to high-growth economies in developing nations.',
                'interests' => ['stocks_and_etfs'],
                'risk_profile' => 'growth',
                'investment_goal' => 'long_term',
                'duration' => 4,
                'duration_type' => 'years',
                'min_investment' => 200,
                'max_investment' => 25000,
                'return_percent' => 15.0,
                'return_interval' => 'yearly',

                'compounding' => true,
                'capital_returned' => true,
            ],

            // Crypto Assets (Growth, Medium Term)
            [
                'name' => 'Bitcoin Accumulator',
                'description' => 'Strategic accumulation of Bitcoin with diversified entry points.',
                'interests' => ['crypto_assets'],
                'risk_profile' => 'growth',
                'investment_goal' => 'medium_term',
                'duration' => 18,
                'duration_type' => 'months',
                'min_investment' => 100,
                'max_investment' => 50000,
                'return_percent' => 5.5,
                'return_interval' => 'monthly', // Monthly staking-like rewards

                'compounding' => false,
                'capital_returned' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'DeFi Yield Farming V2',
                'description' => 'High-yield opportunities in decentralized finance protocols.',
                'interests' => ['crypto_assets'],
                'risk_profile' => 'growth',
                'investment_goal' => 'short_term',
                'duration' => 6,
                'duration_type' => 'months',
                'min_investment' => 500,
                'max_investment' => 10000,
                'return_percent' => 25.0, // annualized
                'return_interval' => 'monthly',

                'compounding' => true,
                'capital_returned' => true,
            ],
            [
                'name' => 'Metaverse Index Fund',
                'description' => 'A basket of top metaverse and gaming tokens.',
                'interests' => ['crypto_assets', 'gaming_and_esports'],
                'risk_profile' => 'growth',
                'investment_goal' => 'long_term',
                'duration' => 2,
                'duration_type' => 'years',
                'min_investment' => 250,
                'max_investment' => 20000,
                'return_percent' => 18.0,
                'return_interval' => 'yearly',

                'compounding' => true,
                'capital_returned' => true,
            ],

            // Real Estate (Balanced, Long Term)
            [
                'name' => 'Commercial Property Trust',
                'description' => 'Invest in premium office spaces and commercial hubs.',
                'interests' => ['real_estate'],
                'risk_profile' => 'balanced',
                'investment_goal' => 'long_term',
                'duration' => 5,
                'duration_type' => 'years',
                'min_investment' => 5000,
                'max_investment' => 500000,
                'return_percent' => 6.5,
                'return_interval' => 'monthly', // Rental income

                'compounding' => false,
                'capital_returned' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Residential Rental Portfolio',
                'description' => 'Pooled investment in residential properties for steady rental yield.',
                'interests' => ['real_estate'],
                'risk_profile' => 'conservative',
                'investment_goal' => 'medium_term',
                'duration' => 24,
                'duration_type' => 'months',
                'min_investment' => 2000,
                'max_investment' => 100000,
                'return_percent' => 7.0,
                'return_interval' => 'monthly',

                'compounding' => true,
                'capital_returned' => true,
            ],
            [
                'name' => 'Luxury Vacation Rentals',
                'description' => 'Short-term rental strategy in high-demand tourist destinations.',
                'interests' => ['real_estate'],
                'risk_profile' => 'balanced',
                'investment_goal' => 'medium_term',
                'duration' => 12,
                'duration_type' => 'months',
                'min_investment' => 3000,
                'max_investment' => 150000,
                'return_percent' => 10.0,
                'return_interval' => 'monthly',

                'compounding' => false,
                'capital_returned' => true,
            ],

            // Fixed Income (Conservative, Short Term)
            [
                'name' => 'Treasury Bonds Series A',
                'description' => 'Government-backed securities offering guaranteed returns.',
                'interests' => ['fixed_income'],
                'risk_profile' => 'conservative',
                'investment_goal' => 'short_term',
                'duration' => 6,
                'duration_type' => 'months',
                'min_investment' => 1000,
                'max_investment' => 1000000,
                'return_percent' => 4.0, // annualized
                'return_interval' => 'monthly',

                'compounding' => true,
                'capital_returned' => true,
            ],
            [
                'name' => 'Corporate Green Bonds',
                'description' => 'Invest in sustainable projects with stable corporate returns.',
                'interests' => ['fixed_income'],
                'risk_profile' => 'conservative',
                'investment_goal' => 'medium_term',
                'duration' => 1,
                'duration_type' => 'years',
                'min_investment' => 500,
                'max_investment' => 50000,
                'return_percent' => 5.2,
                'return_interval' => 'yearly',

                'compounding' => true,
                'capital_returned' => true,
            ],
            [
                'name' => 'High-Yield SavingsPlus',
                'description' => 'Enhanced savings product with better rates than traditional banks.',
                'interests' => ['cash_and_savings'],
                'risk_profile' => 'conservative',
                'investment_goal' => 'short_term',
                'duration' => 30,
                'duration_type' => 'days',
                'min_investment' => 50,
                'max_investment' => 20000,
                'return_percent' => 3.5, // annualized
                'return_interval' => 'monthly',

                'compounding' => true,
                'capital_returned' => true,
            ],

            // Commodities (Balanced, Medium Term)
            [
                'name' => 'Gold Bullion Vault',
                'description' => 'Direct ownership of physical gold stored in secure vaults.',
                'interests' => ['commodities'],
                'risk_profile' => 'conservative',
                'investment_goal' => 'long_term',
                'duration' => 3,
                'duration_type' => 'years',
                'min_investment' => 1000,
                'max_investment' => 500000,
                'return_percent' => 6.0,
                'return_interval' => 'yearly',

                'compounding' => true,
                'capital_returned' => true,
            ],
            [
                'name' => 'Energy Sector ETF',
                'description' => 'Focus on oil, gas, and renewable energy commodities.',
                'interests' => ['commodities'],
                'risk_profile' => 'balanced',
                'investment_goal' => 'medium_term',
                'duration' => 12,
                'duration_type' => 'months',
                'min_investment' => 500,
                'max_investment' => 50000,
                'return_percent' => 9.0,
                'return_interval' => 'monthly',

                'compounding' => false,
                'capital_returned' => true,
            ],
            [
                'name' => 'Agri-Business Futures',
                'description' => 'Investment in agricultural staples like wheat, corn, and soy.',
                'interests' => ['commodities'],
                'risk_profile' => 'growth',
                'investment_goal' => 'short_term',
                'duration' => 3,
                'duration_type' => 'months',
                'min_investment' => 1000,
                'max_investment' => 25000,
                'return_percent' => 12.0, // annualized
                'return_interval' => 'monthly',

                'compounding' => true,
                'capital_returned' => true,
            ],

            // Businesses & Startups (Growth, Long Term)
            [
                'name' => 'Silicon Valley Seed Fund',
                'description' => 'Early-stage investment in promising tech startups.',
                'interests' => ['businesses_and_startups'],
                'risk_profile' => 'growth',
                'investment_goal' => 'long_term',
                'duration' => 5,
                'duration_type' => 'years',
                'min_investment' => 5000,
                'max_investment' => 500000,
                'return_percent' => 20.0,
                'return_interval' => 'yearly',

                'compounding' => true,
                'capital_returned' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Green Energy Innovations',
                'description' => 'Funding startups focused on sustainable energy solutions.',
                'interests' => ['businesses_and_startups'],
                'risk_profile' => 'growth',
                'investment_goal' => 'long_term',
                'duration' => 4,
                'duration_type' => 'years',
                'min_investment' => 1000,
                'max_investment' => 100000,
                'return_percent' => 15.0,
                'return_interval' => 'yearly',

                'compounding' => true,
                'capital_returned' => true,
            ],
            [
                'name' => 'Small Business Lending',
                'description' => 'Providing capital to established small businesses for expansion.',
                'interests' => ['businesses_and_startups'],
                'risk_profile' => 'balanced',
                'investment_goal' => 'medium_term',
                'duration' => 18,
                'duration_type' => 'months',
                'min_investment' => 500,
                'max_investment' => 50000,
                'return_percent' => 8.5,
                'return_interval' => 'monthly',

                'compounding' => true,
                'capital_returned' => true,
            ],

            // Art & Collectibles (Balanced, Long Term)
            [
                'name' => 'Fine Art Fractionals',
                'description' => 'Shared ownership of blue-chip artwork.',
                'interests' => ['art_and_collectibles'],
                'risk_profile' => 'balanced',
                'investment_goal' => 'long_term',
                'duration' => 7,
                'duration_type' => 'years',
                'min_investment' => 5000,
                'max_investment' => 1000000,
                'return_percent' => 11.0,
                'return_interval' => 'yearly',

                'compounding' => false,
                'capital_returned' => true,
            ],
            [
                'name' => 'Rare Wine & Spirits',
                'description' => 'Investment in vintage wines and whiskey casks.',
                'interests' => ['art_and_collectibles'],
                'risk_profile' => 'balanced',
                'investment_goal' => 'long_term',
                'duration' => 5,
                'duration_type' => 'years',
                'min_investment' => 2000,
                'max_investment' => 50000,
                'return_percent' => 10.0,
                'return_interval' => 'yearly',

                'compounding' => true,
                'capital_returned' => true,
            ],
            [
                'name' => 'NFT Blue Chips',
                'description' => 'Exposure to high-value NFT collections like BAYC and Punks.',
                'interests' => ['art_and_collectibles', 'crypto_assets'],
                'risk_profile' => 'growth',
                'investment_goal' => 'medium_term',
                'duration' => 12,
                'duration_type' => 'months',
                'min_investment' => 500,
                'max_investment' => 50000,
                'return_percent' => 18.0,
                'return_interval' => 'monthly',

                'compounding' => true,
                'capital_returned' => true,
            ],

            // Gaming & Esports (Growth, Medium Term)
            [
                'name' => 'Esports Team Equity',
                'description' => 'Invest in top-tier professional esports organizations.',
                'interests' => ['gaming_and_esports'],
                'risk_profile' => 'growth',
                'investment_goal' => 'long_term',
                'duration' => 3,
                'duration_type' => 'years',
                'min_investment' => 500,
                'max_investment' => 50000,
                'return_percent' => 14.0,
                'return_interval' => 'yearly',

                'compounding' => true,
                'capital_returned' => true,
            ],
            [
                'name' => 'GameFi Development Fund',
                'description' => 'Supporting the development of blockchain-based games.',
                'interests' => ['gaming_and_esports', 'crypto_assets'],
                'risk_profile' => 'growth',
                'investment_goal' => 'medium_term',
                'duration' => 24,
                'duration_type' => 'months',
                'min_investment' => 250,
                'max_investment' => 25000,
                'return_percent' => 16.5,
                'return_interval' => 'monthly',

                'compounding' => true,
                'capital_returned' => true,
                'is_featured' => true,
            ],

            // Mixed / Diversified
            [
                'name' => 'Balanced Growth Portfolio',
                'description' => 'A mix of stocks, bonds, and real estate for stable growth.',
                'interests' => ['stocks_and_etfs', 'fixed_income', 'real_estate'],
                'risk_profile' => 'balanced',
                'investment_goal' => 'medium_term',
                'duration' => 2,
                'duration_type' => 'years',
                'min_investment' => 1000,
                'max_investment' => 100000,
                'return_percent' => 8.0,
                'return_interval' => 'monthly',

                'compounding' => true,
                'capital_returned' => true,
            ],
            [
                'name' => 'Aggressive Growth Mix',
                'description' => 'Heavy exposure to crypto and startups for maximum returns.',
                'interests' => ['crypto_assets', 'businesses_and_startups'],
                'risk_profile' => 'growth',
                'investment_goal' => 'long_term',
                'duration' => 3,
                'duration_type' => 'years',
                'min_investment' => 500,
                'max_investment' => 50000,
                'return_percent' => 22.0,
                'return_interval' => 'yearly',
                'compounding' => true,
                'capital_returned' => true,
            ],

        ];

        foreach ($plans as $plan) {
            $plan['return_interval'] = preferredInterval(
                $plan['duration'],
                $plan['duration_type']
            );

            InvestmentPlan::updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }

        //hourly return plans
        $hourlyPlans = [
            [
                'name' => 'Market Volatility Booster',
                'description' => 'An active capital strategy that capitalizes on rapid market swings and price inefficiencies to generate accelerated hourly returns during high-volatility trading windows.',
                'interests' => ['stocks_and_etfs', 'crypto_assets'],
                'risk_profile' => 'growth',
                'investment_goal' => 'short_term',
                'duration' => 48,
                'duration_type' => 'hours',
                'min_investment' => 300,
                'max_investment' => 25000,
                'return_percent' => 6.0,
                'return_interval' => 'hourly',
                'compounding' => true,
                'capital_returned' => true,
            ],
            [
                'name' => 'Overnight Liquidity Pool',
                'description' => 'A capital allocation model that routes funds into short-term liquidity channels and settlement pools designed to produce steady hourly earnings during low-activity market cycles.',
                'interests' => ['fixed_income', 'cash_and_savings'],
                'risk_profile' => 'conservative',
                'investment_goal' => 'short_term',
                'duration' => 24,
                'duration_type' => 'hours',
                'min_investment' => 100,
                'max_investment' => 10000,
                'return_percent' => 2.5,
                'return_interval' => 'hourly',
                'compounding' => false,
                'capital_returned' => true,
            ],
            [
                'name' => 'Flash Crypto Scalper',
                'description' => 'A high-speed trading strategy that executes rapid micro-position entries across major crypto pairs to extract short-term price movements and convert them into rolling hourly gains.',
                'interests' => ['crypto_assets'],
                'risk_profile' => 'growth',
                'investment_goal' => 'short_term',
                'duration' => 72,
                'duration_type' => 'hours',
                'min_investment' => 500,
                'max_investment' => 50000,
                'return_percent' => 9.0,
                'return_interval' => 'hourly',
                'compounding' => true,
                'capital_returned' => true,
            ]

        ];

        foreach ($hourlyPlans as $plan) {
            InvestmentPlan::updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
}
