<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\WithdrawalMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Services\NowpaymentService;
use Illuminate\Support\Facades\Log;
class NowpaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $api_key = config('site.nowpayment_api_key');

        //make a payment method for nowpayment
        $nowpayment = new NowpaymentService();
        $available_currencies = $nowpayment->getAvailableCurrencies();

        $path_to_np_data = public_path('assets/json/nowpayment-currencies.json');
        // fallback
        $nowpayment_currencies = json_decode(file_get_contents($path_to_np_data), true);

        if ($available_currencies['status'] == true) {
            $nowpayment_currencies = $available_currencies['data']['currencies'];
            // update incase new currience added
            file_put_contents($path_to_np_data, json_encode($nowpayment_currencies));
        }


        $payment_information = [];

        $enabled_currencies = [
            "BTC",
            "ETH",
            "XRP",
            "LTC",
            "SOL",
            "TRX",
            "DAI",
            "USDTTRC20",
            "USDTBSC",
            "USDTERC20",
            "USDTSOL",
            "USDC",
            "USDCTRC20",
            "USDCBSC",

        ];

        foreach ($nowpayment_currencies as $currency) {
            $code = $currency['code'];
            if (in_array($code, $enabled_currencies)) {
                $currency['status'] = 'enabled';
            } else {
                $currency['status'] = 'disabled';
            }
            $payment_information[] = $currency;
        }

        $method = [
            'name' => 'Nowpayments.io',
            'logo' => 'nowpayments.ico',
            'type' => 'crypto',
            'class' => 'automatic',
            'pay' => 'nowpayments',
            'payment_information' => json_encode($payment_information),
            'status' => 'enabled',
        ];

        // create or update
        PaymentMethod::updateOrCreate(['name' => $method['name']], $method);
        WithdrawalMethod::updateOrCreate(['name' => $method['name']], $method);
    }
}
