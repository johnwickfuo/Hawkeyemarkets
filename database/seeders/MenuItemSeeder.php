<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuItem::truncate();
        MenuItem::truncate();

        // User Dashboard
        MenuItem::firstOrCreate(
            ['route_name' => 'user.dashboard'],
            [
                'label' => 'Dashboard',
                'url' => null, // generated from route
                'type' => 'user',
                'sort_order' => 1,
                'is_active' => true,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1" /><rect width="7" height="5" x="14" y="3" rx="1" /><rect width="7" height="9" x="14" y="12" rx="1" /><rect width="7" height="5" x="3" y="16" rx="1" /></svg>',
            ]
        );

        // KYC
        MenuItem::updateOrCreate(
            ['route_name' => 'user.kyc'],
            [
                'label' => 'KYC',
                'url' => null, // generated from route
                'type' => 'user',
                'sort_order' => 2,
                'is_active' => true,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>',
            ]
        );

        // Deposits
        $deposit_parent = MenuItem::create(
            [
                'route_name' => null,
                'label' => 'Deposits',
                'url' => "#",
                'type' => 'user',
                'sort_order' => 3,
                'is_active' => true,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><path d="M7 7h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h12"/></svg>',
            ]
        );

        // Sub Deposit items
        MenuItem::create([
            'label' => "Deposit History",
            'route_name' => 'user.deposits.index',
            'url' => null,
            'type' => 'user',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => $deposit_parent->id,
            'route_wildcard' => 'user.deposits.*',
        ]);

        MenuItem::create([
            'label' => "New Deposit",
            'route_name' => 'user.deposits.new',
            'url' => null,
            'type' => 'user',
            'sort_order' => 2,
            'is_active' => true,
            'parent_id' => $deposit_parent->id,
            'route_wildcard' => 'user.deposits.*',
        ]);

        MenuItem::create([
            'label' => "Pending Deposits",
            'route_name' => 'user.deposits.pending',
            'url' => null,
            'type' => 'user',
            'sort_order' => 3,
            'is_active' => true,
            'parent_id' => $deposit_parent->id,
            'route_wildcard' => 'user.deposits.*',
        ]);

        MenuItem::create([
            'label' => "Approved Deposits",
            'route_name' => 'user.deposits.approved',
            'url' => null,
            'type' => 'user',
            'sort_order' => 4,
            'is_active' => true,
            'parent_id' => $deposit_parent->id,
            'route_wildcard' => 'user.deposits.*',
        ]);

        MenuItem::create([
            'label' => "Failed Deposits",
            'route_name' => 'user.deposits.failed',
            'url' => null,
            'type' => 'user',
            'sort_order' => 5,
            'is_active' => true,
            'parent_id' => $deposit_parent->id,
            'route_wildcard' => 'user.deposits.*',
        ]);

        // Investments
        $investment_parent = MenuItem::create(
            [
                'route_name' => null,
                'label' => 'Managed Investments',
                'url' => "#",
                'type' => 'user',
                'sort_order' => 6,
                'is_active' => true,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>',

            ]
        );

        // Sub Investment items
        MenuItem::create([
            'label' => "Investment History",
            'route_name' => 'user.investments.index',
            'url' => null,
            'type' => 'user',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => $investment_parent->id,
            'route_wildcard' => 'user.investments.*',
        ]);

        MenuItem::create([
            'label' => "New Investment",
            'route_name' => 'user.investments.new',
            'url' => null,
            'type' => 'user',
            'sort_order' => 2,
            'is_active' => true,
            'parent_id' => $investment_parent->id,
            'route_wildcard' => 'user.investments.*',
        ]);

        MenuItem::create([
            'label' => "Investment Earnings",
            'route_name' => 'user.investments.earnings',
            'url' => null,
            'type' => 'user',
            'sort_order' => 3,
            'is_active' => true,
            'parent_id' => $investment_parent->id,
            'route_wildcard' => 'user.investments.*',
        ]);

        $sector_parent = MenuItem::create([
            'route_name' => null,
            'label' => 'Sectors',
            'url' => "#",
            'type' => 'user',
            'sort_order' => 7,
            'is_active' => true,
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>',
        ]);

        // Sectors submenu
        $sectors = array_keys(config('interests'));
        foreach ($sectors as $sector) {
            MenuItem::create([
                'label' => $sector,
                'route_name' => 'user.sectors.' . $sector,
                'url' => null,
                'type' => 'user',
                'sort_order' => 1,
                'is_active' => true,
                'parent_id' => $sector_parent->id,
                'route_wildcard' => 'user.sectors.*',
            ]);
        }


        // Trading Menu
        $trading_parent = MenuItem::create([
            'route_name' => null,
            'label' => 'Self Trading',
            'url' => "#",
            'type' => 'user',
            'sort_order' => 5,
            'is_active' => true,
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5v4"/><rect width="4" height="6" x="7" y="9" rx="1"/><path d="M9 15v2"/><path d="M17 3v2"/><rect width="4" height="8" x="15" y="5" rx="1"/><path d="M17 13v3"/><path d="M3 3v18h18"/></svg>',
        ]);
        // Trading submenu
        MenuItem::create([
            'label' => "Account Overview",
            'route_name' => 'user.trading.account',
            'url' => null,
            'type' => 'user',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => $trading_parent->id,
            'route_wildcard' => 'user.trading.*',
        ]);

        // Trading submenu
        MenuItem::create([
            'label' => "Futures Trading",
            'route_name' => 'user.trading.futures',
            'url' => null,
            'type' => 'user',
            'sort_order' => 2,
            'is_active' => true,
            'parent_id' => $trading_parent->id,
            'route_wildcard' => 'user.trading.*',
        ]);

        MenuItem::create([
            'label' => "Margin Trading",
            'route_name' => 'user.trading.margin',
            'url' => null,
            'type' => 'user',
            'sort_order' => 3,
            'is_active' => true,
            'parent_id' => $trading_parent->id,
            'route_wildcard' => 'user.trading.*',
        ]);
        MenuItem::create([
            'label' => "Forex Trading (Live)",
            'route_name' => 'user.trading.forex.live',
            'url' => null,
            'type' => 'user',
            'sort_order' => 4,
            'is_active' => true,
            'parent_id' => $trading_parent->id,
            'route_wildcard' => 'user.trading.*',
        ]);
        MenuItem::create([
            'label' => "Forex Trading (Demo)",
            'route_name' => 'user.trading.forex.demo',
            'url' => null,
            'type' => 'user',
            'sort_order' => 5,
            'is_active' => true,
            'parent_id' => $trading_parent->id,
            'route_wildcard' => 'user.trading.*',
        ]);

        // MenuItem::create([
        //     'label' => "Commodity Trading",
        //     'route_name' => 'user.trading.commodity',
        //     'url' => null,
        //     'type' => 'user',
        //     'sort_order' => 5,
        //     'is_active' => true,
        //     'parent_id' => $trading_parent->id,
        //     'route_wildcard' => 'user.trading.*',
        // ]);

        // Capital Instruments
        $capital_instruments_parent = MenuItem::create([
            'route_name' => null,
            'label' => 'Capital Instruments',
            'url' => "#",
            'type' => 'user',
            'sort_order' => 4,
            'is_active' => true,
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 6V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1"/><path d="M4 9a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V9z"/><path d="M7.5 16l2.5-2.5 2 2 4-4"/><path d="M17 11v3h-3"/></svg>',
        ]);

        // Capital Instruments submenu
        MenuItem::create([
            'label' => "Stocks",
            'route_name' => 'user.capital-instruments.stocks',
            'url' => null,
            'type' => 'user',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => $capital_instruments_parent->id,
            'route_wildcard' => 'user.capital-instruments.*',
        ]);

        MenuItem::create([
            'label' => "Bonds",
            'route_name' => 'user.capital-instruments.bonds',
            'url' => null,
            'type' => 'user',
            'sort_order' => 2,
            'is_active' => true,
            'parent_id' => $capital_instruments_parent->id,
            'route_wildcard' => 'user.capital-instruments.*',
        ]);

        // MenuItem::create([
        //     'label' => "Commercial Papers",
        //     'route_name' => 'user.capital-instruments.commercial-papers',
        //     'url' => null,
        //     'type' => 'user',
        //     'sort_order' => 3,
        //     'is_active' => true,
        //     'parent_id' => $capital_instruments_parent->id,
        //     'route_wildcard' => 'user.capital-instruments.*',
        // ]);

        MenuItem::create([
            'label' => "ETFs",
            'route_name' => 'user.capital-instruments.etfs',
            'url' => null,
            'type' => 'user',
            'sort_order' => 4,
            'is_active' => true,
            'parent_id' => $capital_instruments_parent->id,
            'route_wildcard' => 'user.capital-instruments.*',
        ]);

        // MenuItem::create([
        //     'label' => "Mutual Funds",
        //     'route_name' => 'user.capital-instruments.mutual-funds',
        //     'url' => null,
        //     'type' => 'user',
        //     'sort_order' => 5,
        //     'is_active' => true,
        //     'parent_id' => $capital_instruments_parent->id,
        //     'route_wildcard' => 'user.capital-instruments.*',
        // ]);

        // MenuItem::create([
        //     'label' => "Treasury Bills",
        //     'route_name' => 'user.capital-instruments.treasury-bills',
        //     'url' => null,
        //     'type' => 'user',
        //     'sort_order' => 6,
        //     'is_active' => false,
        //     'parent_id' => $capital_instruments_parent->id,
        //     'route_wildcard' => 'user.capital-instruments.*',
        // ]);


        // Withdrawals
        $withdrawals_parent = MenuItem::create(
            [
                'route_name' => null,
                'label' => 'Withdrawals',
                'url' => "#",
                'type' => 'user',
                'sort_order' => 9,
                'is_active' => true,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>',
            ]
        );

        // Sub Withdrawal items
        MenuItem::create([
            'label' => "Withdrawal History",
            'route_name' => 'user.withdrawals.index',
            'url' => null,
            'type' => 'user',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => $withdrawals_parent->id,
            'route_wildcard' => 'user.withdrawals.*',
        ]);

        MenuItem::create([
            'label' => "New Withdrawal",
            'route_name' => 'user.withdrawals.new',
            'url' => null,
            'type' => 'user',
            'sort_order' => 2,
            'is_active' => true,
            'parent_id' => $withdrawals_parent->id,
            'route_wildcard' => 'user.withdrawals.*',
        ]);

        MenuItem::create([
            'label' => "Pending Withdrawals",
            'route_name' => 'user.withdrawals.pending',
            'url' => null,
            'type' => 'user',
            'sort_order' => 3,
            'is_active' => true,
            'parent_id' => $withdrawals_parent->id,
            'route_wildcard' => 'user.withdrawals.*',
        ]);

        MenuItem::create([
            'label' => "Approved Withdrawals",
            'route_name' => 'user.withdrawals.approved',
            'url' => null,
            'type' => 'user',
            'sort_order' => 4,
            'is_active' => true,
            'parent_id' => $withdrawals_parent->id,
            'route_wildcard' => 'user.withdrawals.*',
        ]);

        MenuItem::create([
            'label' => "Failed Withdrawals",
            'route_name' => 'user.withdrawals.failed',
            'url' => null,
            'type' => 'user',
            'sort_order' => 5,
            'is_active' => true,
            'parent_id' => $withdrawals_parent->id,
            'route_wildcard' => 'user.withdrawals.*',
        ]);

        // Transactions
        MenuItem::updateOrCreate(
            ['route_name' => 'user.transactions'],
            [
                'label' => 'Transactions',
                'url' => null, // generated from route
                'type' => 'user',
                'sort_order' => 10,
                'is_active' => true,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3 4 7l4 4"/><path d="M4 7h16"/><path d="m16 21 4-4-4-4"/><path d="M20 17H4"/></svg>',
            ]
        );


        // referrals
        MenuItem::updateOrCreate(
            ['route_name' => 'user.referrals'],
            [
                'label' => 'Referrals',
                'url' => null, // generated from route
                'type' => 'user',
                'sort_order' => 11,
                'is_active' => true,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            ]
        );




        // ADMIN ROUTES STARTS HERE
        // Admin Dashboard
        MenuItem::updateOrCreate(
            ['route_name' => 'admin.dashboard', 'type' => 'admin'],
            [
                'label' => 'Dashboard',
                'url' => null,
                'sort_order' => 1,
                'is_active' => true,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1" /><rect width="7" height="5" x="14" y="3" rx="1" /><rect width="7" height="9" x="14" y="12" rx="1" /><rect width="7" height="5" x="3" y="16" rx="1" /></svg>',
            ]
        );

        // User management
        $users_parent = MenuItem::create(
            [
                'route_name' => null,
                'label' => 'Users',
                'url' => "#",
                'type' => 'admin',
                'sort_order' => 6,
                'is_active' => true,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            ]
        );

        // Users submenu
        MenuItem::create([
            'label' => "All Users",
            'route_name' => 'admin.users.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => $users_parent->id,
            'route_wildcard' => 'admin.users.*',
        ]);

        MenuItem::create([
            'label' => "KYC Verified",
            'route_name' => 'admin.users.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => $users_parent->id,
            'route_wildcard' => 'admin.users.*',
            'params' => ['kyc_status' => 'approved']
        ]);

        MenuItem::create([
            'label' => "Email Verified",
            'route_name' => 'admin.users.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 2,
            'is_active' => true,
            'parent_id' => $users_parent->id,
            'route_wildcard' => 'admin.users.*',
            'params' => ['email_verified' => '1']
        ]);

        MenuItem::create([
            'label' => "Active Users",
            'route_name' => 'admin.users.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 3,
            'is_active' => true,
            'parent_id' => $users_parent->id,
            'route_wildcard' => 'admin.users.*',
            'params' => ['status' => 'active']
        ]);

        MenuItem::create([
            'label' => "Banned Users",
            'route_name' => 'admin.users.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 4,
            'is_active' => true,
            'parent_id' => $users_parent->id,
            'route_wildcard' => 'admin.users.*',
            'params' => ['status' => 'banned']
        ]);

        MenuItem::create([
            'label' => "Bulk Email",
            'route_name' => 'admin.users.bulk-email',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 5,
            'is_active' => true,
            'parent_id' => $users_parent->id,
            'route_wildcard' => 'admin.users.*',
        ]);


        // Investment
        $investment_parent = MenuItem::create(
            [
                'route_name' => null,
                'label' => 'Investments',
                'url' => "#",
                'type' => 'admin',
                'sort_order' => 3,
                'is_active' => true,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 7V6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1" /><path d="M6 7h12a2 2 0 0 1 2 2v7a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V9a2 2 0 0 1 2-2Z" /><path d="M4 12h16" /><circle cx="18" cy="6" r="2" /><path d="M18 5.2v1.6" /></svg>',
            ]
        );

        // investment plans, subparent
        MenuItem::create([
            'label' => "All Plans",
            'route_name' => 'admin.investments.plans.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => $investment_parent->id,
            'route_wildcard' => 'admin.investments.plans.*',
        ]);

        MenuItem::create([
            'label' => "Create Plan",
            'route_name' => 'admin.investments.plans.create',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 2,
            'is_active' => true,
            'parent_id' => $investment_parent->id,
            'route_wildcard' => 'admin.investments.plans.*',
        ]);

        MenuItem::create([
            'label' => "Investment History",
            'route_name' => 'admin.investments.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 3,
            'is_active' => true,
            'parent_id' => $investment_parent->id,
            'route_wildcard' => 'admin.investments.*',
        ]);

        MenuItem::create([
            'label' => "Active Investments",
            'route_name' => 'admin.investments.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 4,
            'is_active' => true,
            'parent_id' => $investment_parent->id,
            'route_wildcard' => 'admin.investments.*',
            'params' => ['status' => 'active']
        ]);

        MenuItem::create([
            'label' => "Completed Investments",
            'route_name' => 'admin.investments.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 5,
            'is_active' => true,
            'parent_id' => $investment_parent->id,
            'route_wildcard' => 'admin.investments.*',
            'params' => ['status' => 'completed']
        ]);

        MenuItem::create([
            'label' => "Suspended Investments",
            'route_name' => 'admin.investments.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 6,
            'is_active' => true,
            'parent_id' => $investment_parent->id,
            'route_wildcard' => 'admin.investments.*',
            'params' => ['status' => 'suspended']
        ]);

        MenuItem::create([
            'label' => "Earning History",
            'route_name' => 'admin.investments.earnings',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 7,
            'is_active' => true,
            'parent_id' => $investment_parent->id,
            'route_wildcard' => 'admin.investments.*',
        ]);


        // Deposits
        $deposit_parent = MenuItem::create(
            [
                'route_name' => null,
                'label' => 'Deposits',
                'url' => "#",
                'type' => 'admin',
                'sort_order' => 4,
                'is_active' => true,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7h14a3 3 0 0 1 3 3v7a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7Z" /><path d="M17 7V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v1" /><path d="M20 12h-4a2 2 0 0 0 0 4h4" /><path d="M12 10v6" /><path d="M9.5 13.5 12 16l2.5-2.5" /></svg>',
            ]
        );

        MenuItem::create([
            'label' => "All Deposits",
            'route_name' => 'admin.deposits.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => $deposit_parent->id,
            'route_wildcard' => 'admin.deposits.*',
        ]);

        MenuItem::create([
            'label' => "Pending Deposits",
            'route_name' => 'admin.deposits.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 2,
            'is_active' => true,
            'parent_id' => $deposit_parent->id,
            'route_wildcard' => 'admin.deposits.*',
            'params' => ['status' => 'pending']
        ]);

        MenuItem::create([
            'label' => "Completed Deposits",
            'route_name' => 'admin.deposits.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 3,
            'is_active' => true,
            'parent_id' => $deposit_parent->id,
            'route_wildcard' => 'admin.deposits.*',
            'params' => ['status' => 'completed']
        ]);

        MenuItem::create([
            'label' => "Failed Deposits",
            'route_name' => 'admin.deposits.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 4,
            'is_active' => true,
            'parent_id' => $deposit_parent->id,
            'route_wildcard' => 'admin.deposits.*',
            'params' => ['status' => 'failed']
        ]);


        // Withdrawals
        $withdrawal_parent = MenuItem::create(
            [
                'route_name' => null,
                'label' => 'Withdrawals',
                'url' => "#",
                'type' => 'admin',
                'sort_order' => 5,
                'is_active' => true,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7h14a3 3 0 0 1 3 3v7a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7Z" /><path d="M17 7V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v1" /><path d="M20 12h-4a2 2 0 0 0 0 4h4" /><path d="M12 16V10" /><path d="M9.5 12.5 12 10l2.5 2.5" /></svg>',
            ]
        );

        MenuItem::create([
            'label' => "All Withdrawals",
            'route_name' => 'admin.withdrawals.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => $withdrawal_parent->id,
            'route_wildcard' => 'admin.withdrawals.*',
        ]);

        MenuItem::create([
            'label' => "Pending Withdrawals",
            'route_name' => 'admin.withdrawals.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 2,
            'is_active' => true,
            'parent_id' => $withdrawal_parent->id,
            'route_wildcard' => 'admin.withdrawals.*',
            'params' => ['status' => 'pending']
        ]);

        MenuItem::create([
            'label' => "Completed Withdrawals",
            'route_name' => 'admin.withdrawals.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 3,
            'is_active' => true,
            'parent_id' => $withdrawal_parent->id,
            'route_wildcard' => 'admin.withdrawals.*',
            'params' => ['status' => 'completed']
        ]);

        MenuItem::create([
            'label' => "Failed Withdrawals",
            'route_name' => 'admin.withdrawals.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 4,
            'is_active' => true,
            'parent_id' => $withdrawal_parent->id,
            'route_wildcard' => 'admin.withdrawals.*',
            'params' => ['status' => 'failed']
        ]);


        // stocks
        $stock_parrent = MenuItem::create([
            'route_name' => null,
            'label' => 'Stocks',
            'url' => "#",
            'type' => 'admin',
            'sort_order' => 7,
            'is_active' => true,
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20V10"/><rect x="3" y="10" width="2" height="6"/><path d="M10 20V6"/><rect x="9" y="8" width="2" height="6"/><path d="M16 20V4"/><rect x="15" y="6" width="2" height="6"/><path d="M2 20h20"/></svg>'
        ]);

        MenuItem::create([
            'label' => "All Holdings",
            'route_name' => 'admin.stocks.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => $stock_parrent->id,
            'route_wildcard' => 'admin.stocks.*',
        ]);

        MenuItem::create([
            'label' => "Order History",
            'route_name' => 'admin.stocks.history',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 2,
            'is_active' => true,
            'parent_id' => $stock_parrent->id,
            'route_wildcard' => 'admin.stocks.*',
        ]);

        // etfs
        $etf_parrent = MenuItem::create([
            'route_name' => null,
            'label' => 'ETFs',
            'url' => "#",
            'type' => 'admin',
            'sort_order' => 7,
            'is_active' => true,
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M3 11h18"/><circle cx="8" cy="15" r="1"/><circle cx="12" cy="15" r="1"/><circle cx="16" cy="15" r="1"/></svg>'
        ]);

        MenuItem::create([
            'label' => "All Holdings",
            'route_name' => 'admin.etfs.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => $etf_parrent->id,
            'route_wildcard' => 'admin.etfs.*',
        ]);

        MenuItem::create([
            'label' => "Order History",
            'route_name' => 'admin.etfs.history',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 2,
            'is_active' => true,
            'parent_id' => $etf_parrent->id,
            'route_wildcard' => 'admin.etfs.*',
        ]);

        // bonds
        $bonds_parrent = MenuItem::create([
            'route_name' => null,
            'label' => 'Bonds',
            'url' => "#",
            'type' => 'admin',
            'sort_order' => 7,
            'is_active' => true,
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"> <path d="M7 4h10a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3z"/> <path d="M8 9h8M8 12h5"/> <circle cx="16.5" cy="15.5" r="2.2"/> <path d="M15.2 15.5h2.6"/> </svg>'
        ]);

        MenuItem::create([
            'label' => "All Bonds",
            'route_name' => 'admin.bonds.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => $bonds_parrent->id,
            'route_wildcard' => 'admin.bonds.*',
        ]);

        MenuItem::create([
            'label' => "Order History",
            'route_name' => 'admin.bonds.history',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 2,
            'is_active' => true,
            'parent_id' => $bonds_parrent->id,
            'route_wildcard' => 'admin.bonds.*',
        ]);


        // Futures Trading
        $futures_parrent = MenuItem::create([
            'route_name' => null,
            'label' => 'Futures Trading',
            'url' => "#",
            'type' => 'admin',
            'sort_order' => 7,
            'is_active' => true,
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"> <path d="M4 17V7a3 3 0 0 1 3-3h10"/> <path d="M6 9h10a3 3 0 0 1 3 3v8"/> <path d="M9 13h7"/> <path d="M16 11l3 2-3 2"/> </svg>'
        ]);

        MenuItem::create([
            'label' => "Trading Accounts",
            'route_name' => 'admin.futures-trading.accounts.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => $futures_parrent->id,
            'route_wildcard' => 'admin.futures-trading.*',
        ]);

        MenuItem::create([
            'label' => "Positions",
            'route_name' => 'admin.futures-trading.positions.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 2,
            'is_active' => true,
            'parent_id' => $futures_parrent->id,
            'route_wildcard' => 'admin.futures-trading.*',
        ]);

        MenuItem::create([
            'label' => "Order History",
            'route_name' => 'admin.futures-trading.orders.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 3,
            'is_active' => true,
            'parent_id' => $futures_parrent->id,
            'route_wildcard' => 'admin.futures-trading.*',
        ]);

        // Margin Trading
        $margin_parrent = MenuItem::create([
            'route_name' => null,
            'label' => 'Margin Trading',
            'url' => "#",
            'type' => 'admin',
            'sort_order' => 7,
            'is_active' => true,
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"> <path d="M10.5 13.5l3-3"/> <path d="M8.5 15.5l-1 1a4 4 0 0 1-5.7-5.7l1-1"/> <path d="M15.5 8.5l1-1a4 4 0 0 1 5.7 5.7l-1 1"/> <path d="M7.5 12.5l-2 2"/> <path d="M16.5 11.5l2-2"/> </svg>'
        ]);

        MenuItem::create([
            'label' => "Trading Accounts",
            'route_name' => 'admin.margin-trading.accounts.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => $margin_parrent->id,
            'route_wildcard' => 'admin.margin-trading.*',
        ]);

        MenuItem::create([
            'label' => "Positions",
            'route_name' => 'admin.margin-trading.positions.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 2,
            'is_active' => true,
            'parent_id' => $margin_parrent->id,
            'route_wildcard' => 'admin.margin-trading.*',
        ]);

        MenuItem::create([
            'label' => "Order History",
            'route_name' => 'admin.margin-trading.orders.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 3,
            'is_active' => true,
            'parent_id' => $margin_parrent->id,
            'route_wildcard' => 'admin.margin-trading.*',
        ]);

        // Forex Trading
        $forex_parrent = MenuItem::create([
            'route_name' => null,
            'label' => 'Forex Trading',
            'url' => "#",
            'type' => 'admin',
            'sort_order' => 7,
            'is_active' => true,
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"> <path d="M7 7h7a4 4 0 0 1 0 8H6"/> <path d="M17 17H10a4 4 0 0 1 0-8h8"/> <path d="M6 15l-2 2 2 2"/> <path d="M18 9l2-2-2-2"/> </svg>'
        ]);

        MenuItem::create([
            'label' => "Trading Accounts",
            'route_name' => 'admin.forex-trading.accounts.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => $forex_parrent->id,
            'route_wildcard' => 'admin.forex-trading.*',
        ]);

        MenuItem::create([
            'label' => "Positions",
            'route_name' => 'admin.forex-trading.positions.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 2,
            'is_active' => true,
            'parent_id' => $forex_parrent->id,
            'route_wildcard' => 'admin.forex-trading.*',
        ]);

        MenuItem::create([
            'label' => "Order History",
            'route_name' => 'admin.forex-trading.orders.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 3,
            'is_active' => true,
            'parent_id' => $forex_parrent->id,
            'route_wildcard' => 'admin.forex-trading.*',
        ]);





        // KYC Records
        $kyc_parent = MenuItem::create(
            [
                'route_name' => null,
                'label' => 'KYC Records',
                'url' => "#",
                'type' => 'admin',
                'sort_order' => 7,
                'is_active' => true,
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V7l8-4Z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0-6 0Z" /><path d="M8.5 18c1.1-1.6 2.8-2.5 3.5-2.5s2.4.9 3.5 2.5" /><path d="M15.8 10.8l1.2 1.2 2.4-2.4" /></svg>'
            ]
        );

        MenuItem::create([
            'label' => "All KYC Records",
            'route_name' => 'admin.kyc.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => $kyc_parent->id,
            'route_wildcard' => 'admin.kyc.*',
        ]);

        MenuItem::create([
            'label' => "Pending",
            'route_name' => 'admin.kyc.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 2,
            'is_active' => true,
            'parent_id' => $kyc_parent->id,
            'route_wildcard' => 'admin.kyc.*',
            'params' => ['status' => 'pending']
        ]);

        MenuItem::create([
            'label' => "Approved",
            'route_name' => 'admin.kyc.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 3,
            'is_active' => true,
            'parent_id' => $kyc_parent->id,
            'route_wildcard' => 'admin.kyc.*',
            'params' => ['status' => 'approved']
        ]);

        MenuItem::create([
            'label' => "Rejected",
            'route_name' => 'admin.kyc.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 4,
            'is_active' => true,
            'parent_id' => $kyc_parent->id,
            'route_wildcard' => 'admin.kyc.*',
            'params' => ['status' => 'rejected']
        ]);

        MenuItem::create([
            'label' => "Transactions",
            'route_name' => 'admin.transactions.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 8,
            'is_active' => true,
            'parent_id' => null,
            'route_wildcard' => 'admin.transactions.*',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h12" /><path d="M15 3l4 4-4 4" /><path d="M17 17H5" /><path d="M9 21l-4-4 4-4" /></svg>'
        ]);

        MenuItem::create([
            'label' => "Referrals",
            'route_name' => 'admin.referrals.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 9,
            'is_active' => true,
            'parent_id' => null,
            'route_wildcard' => 'admin.referrals.*',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"> <circle cx="12" cy="6" r="2"/> <circle cx="4" cy="18" r="2"/> <circle cx="12" cy="18" r="2"/> <circle cx="20" cy="18" r="2"/> <path d="M12 8v4"/> <path d="M12 12H4"/> <path d="M12 12H20"/> <path d="M12 12v4"/> </svg>'

        ]);

        MenuItem::create([
            'label' => "Settings",
            'route_name' => 'admin.settings.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 10,
            'is_active' => true,
            'parent_id' => null,
            'route_wildcard' => 'admin.settings.*',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"> <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3h0 a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8 1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1Z"/> </svg>'

        ]);

        MenuItem::create([
            'label' => "File Manager",
            'route_name' => 'admin.file-manager.index',
            'url' => null,
            'type' => 'admin',
            'sort_order' => 11,
            'is_active' => true,
            'parent_id' => null,
            'route_wildcard' => 'admin.file-manager.*',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"> <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v2H3z"/> <rect x="3" y="9" width="18" height="10" rx="2"/> <rect x="7" y="12" width="2" height="2" rx=".3"/> <rect x="11" y="12" width="2" height="2" rx=".3"/> <rect x="15" y="12" width="2" height="2" rx=".3"/> </svg>'
        ]);


        // insert default admin — find by email OR username (each is independently unique)
        $admin = \App\Models\Admin::first();

        if (!$admin) {
            \App\Models\Admin::create([
                'name' => 'Super Admin',
                'email' => 'admin@admin.com',
                'username' => 'admin',
                'password' => \Hash::make('password'),
            ]);
        }
        // Clear cache
        Artisan::call('cache:clear');

    }
}
