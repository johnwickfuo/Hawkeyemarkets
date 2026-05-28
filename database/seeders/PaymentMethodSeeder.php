<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\WithdrawalMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    // Logos from https://worldvectorlogo.com/
    public function run(): void
    {

        // deposits
        $methods = [
            [
                'name' => 'Bitcoin',
                'logo' => 'bitcoin.svg',
                'type' => 'crypto',
                'class' => 'manual',
                'pay' => 'manual',
                'payment_information' => [
                    "wallet_address" => "bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh",
                    "network" => "Bitcoin",
                    'currency' => 'BTC',
                    "instructions" => "Send only BTC to this address. Sending any other asset may result in permanent loss."
                ],
                'status' => 'enabled',
            ],
            [
                'name' => 'Ethereum',
                'logo' => 'ethereum.svg',
                'type' => 'crypto',
                'class' => 'manual',
                'pay' => 'manual',
                'payment_information' => [
                    "wallet_address" => "0x71C7656EC7ab88b098defB751B7401B5f6d8976F",
                    "network" => "ERC20",
                    'currency' => 'ETH',
                    "instructions" => "Send only ETH to this address."
                ],
                'status' => 'enabled',
            ],
            [
                'name' => 'USDT (TRC20)',
                'logo' => 'usdt.svg',
                'type' => 'crypto',
                'class' => 'manual',
                'pay' => 'manual',
                'payment_information' => [
                    "wallet_address" => "TYDzsYUEpvrYmNyDGz1fxKaK1",
                    "network" => "TRC20",
                    'currency' => 'USDT',
                    "instructions" => "Send only USDT TRC20 to this address."
                ],
                'status' => 'enabled',
            ],
            [
                'name' => 'Bank Transfer',
                'logo' => 'chase.svg',
                'type' => 'bank_transfer',
                'class' => 'manual',
                'pay' => 'manual',
                'payment_information' => [
                    "bank_name" => "Chase Bank",
                    "account_holder" => "Lozand LLC",
                    "account_number" => "9876543210",
                    "routing_number" => "123456789",
                    "swift" => "CHASEUS33",
                    'currency' => null,
                    "instructions" => "Please include your username in the transaction reference."
                ],
                'status' => 'enabled',
            ],
            [
                'name' => 'Paypal',
                'logo' => 'paypal.svg',
                'type' => 'digital_wallet',
                'class' => 'manual',
                'pay' => 'manual',
                'payment_information' => [
                    'email' => 'paypal@paypal.com',
                    'tag' => null,
                    'phone' => null,
                    'username' => null,
                    'currency' => null,
                    'instructions' => "Please include your username in the transaction reference."
                ],
                'status' => 'enabled',
            ],
        ];

        // truncate the payment method table


        foreach ($methods as $method) {
            // encode the payment information first
            $method['payment_information'] = json_encode($method['payment_information']);
            // create or update
            PaymentMethod::updateOrCreate(['name' => $method['name']], $method);
        }


        // withdrawal
        $withdrawal_methods = [
            [
                'name' => 'Bitcoin',
                'logo' => 'bitcoin.svg',
                'type' => 'crypto',
                'class' => 'manual',
                'pay' => null,
                'payment_information' => [
                    'fields' => [
                        "wallet_address" => "string|required|max:255",
                    ],
                    "network" => "Bitcoin",
                    'currency' => 'BTC',
                    "instructions" => "Ensure the wallet address is correct and belongs to you. We are not responsible for any lost funds due to incorrect wallet address."
                ],
                'status' => 'enabled',
            ],
            [
                'name' => 'Ethereum',
                'logo' => 'ethereum.svg',
                'type' => 'crypto',
                'class' => 'manual',
                'pay' => null,
                'payment_information' => [
                    'fields' => [
                        "wallet_address" => "string|required|max:255",
                    ],
                    "network" => "ERC20",
                    'currency' => 'ETH',
                    "instructions" => "Ensure the wallet address is correct and belongs to you. We are not responsible for any lost funds due to incorrect wallet address."
                ],
                'status' => 'enabled',
            ],
            [
                'name' => 'USDT (TRC20)',
                'logo' => 'usdt.svg',
                'type' => 'crypto',
                'class' => 'manual',
                'pay' => null,
                'payment_information' => [
                    'fields' => [
                        "wallet_address" => "string|required|max:255",
                    ],
                    "network" => "TRC20",
                    'currency' => 'USDT',
                    "instructions" => "Ensure the wallet address is correct and belongs to you. We are not responsible for any lost funds due to incorrect wallet address."
                ],
                'status' => 'enabled',
            ],
            [
                'name' => 'Bank Transfer',
                'logo' => 'chase.svg',
                'type' => 'bank_transfer',
                'class' => 'manual',
                'pay' => null,
                'payment_information' => [
                    'fields' => [
                        "bank_name" => "string|required|max:255",
                        "account_holder" => "string|required|max:255",
                        "account_number" => "string|required|max:255",
                        "routing_number" => "string|required|max:255",
                        "swift" => "string|required|max:255",
                    ],
                    'currency' => null,
                    "instructions" => "Ensure the bank details are correct and belongs to you. We are not responsible for any lost funds due to incorrect bank details."
                ],
                'status' => 'enabled',
            ],
            [
                'name' => 'Paypal',
                'logo' => 'paypal.svg',
                'type' => 'digital_wallet',
                'class' => 'manual',
                'pay' => null,
                'payment_information' => [
                    'fields' => [
                        "email" => "email|required|max:255",
                    ],
                    'currency' => null,
                    'instructions' => "Ensure the information provided are correct."
                ],
                'status' => 'enabled',
            ],
        ];



        foreach ($withdrawal_methods as $method) {
            // encode the payment information first
            $method['payment_information'] = json_encode($method['payment_information']);
            // create or update
            WithdrawalMethod::updateOrCreate(['name' => $method['name']], $method);
        }
    }
}
