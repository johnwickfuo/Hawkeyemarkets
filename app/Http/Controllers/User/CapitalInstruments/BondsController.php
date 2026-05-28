<?php

namespace App\Http\Controllers\User\CapitalInstruments;

use App\Http\Controllers\Controller;
use App\Models\BondHolding;
use App\Models\BondHoldingHistory;
use App\Services\LozandServices;
use Illuminate\Http\Request;

class BondsController extends Controller
{
    public function __construct()
    {
        if (!moduleEnabled('bonds_module')) {
            abort(404);
        }
    }

    public function index()
    {
        $page_title = __("Bonds");
        $template = config('site.template');

        $lozand = new LozandServices();
        $bonds_data = $lozand->bonds();
        $bonds = [];

        if ($bonds_data['status'] == 'success') {
            $bonds = $bonds_data['data'];
        }

        $message = $bonds_data['message'] ?? null;

        $userId = auth()->id();
        $active_holdings = BondHolding::where('user_id', $userId)->active()->get();
        $holding_histories = BondHoldingHistory::where('user_id', $userId)->latest()->paginate(getSetting('pagination'));

        // simple analytics for bonds
        $total_invested = $active_holdings->sum('amount');
        $total_expected_interest = $active_holdings->sum('interest_amount');

        $bond_analytics = [
            'active_count' => $active_holdings->count(),
            'total_invested' => $total_invested,
            'expected_interest' => $total_expected_interest,
            'total_payouts' => BondHoldingHistory::where('user_id', $userId)->where('transaction_type', 'payout')->sum('amount'),
        ];

        return view("templates.{$template}.blades.user.capital-instruments.bonds.index", compact('page_title', 'bonds', 'active_holdings', 'holding_histories', 'message', 'bond_analytics'));
    }

    public function buyBond($ticker)
    {
        $page_title = __("Buy Bond");
        $template = config('site.template');

        $lozand = new LozandServices();
        $bond_data = $lozand->bond($ticker);
        $bond = [];

        if ($bond_data['status'] == 'success') {
            $bond = $bond_data['data'];
        }

        $message = $bond_data['message'] ?? null;

        return view("templates.{$template}.blades.user.capital-instruments.bonds.buy", compact('page_title', 'bond', 'message'));
    }

    public function buyBondValidate(Request $request, $ticker)
    {
        $min_bond_purchase = getSetting('min_bond_purchase', 100);
        $max_bond_purchase = getSetting('max_bond_purchase', 1000000);
        $bond_purchase_fee_percent = getSetting('bond_purchase_fee_percent', 0);

        $request->validate([
            'amount' => 'required|numeric',
        ]);

        $amount = $request->amount;
        $user = auth()->user();

        if ($amount < $min_bond_purchase || $amount > $max_bond_purchase) {
            return response()->json([
                'status' => 'error',
                'message' => __('Amount must be between :min and :max', ['min' => $min_bond_purchase, 'max' => $max_bond_purchase])
            ], 422);
        }

        $fee_amount = $amount * ($bond_purchase_fee_percent / 100);
        $total_cost = $amount + $fee_amount;

        if ($user->balance < $total_cost) {
            return response()->json([
                'status' => 'error',
                'message' => __('Insufficient balance')
            ], 422);
        }

        $lozand = new LozandServices();
        $bond_fetched = $lozand->bond($ticker);

        if ($bond_fetched['status'] == 'error') {
            return response()->json(['status' => 'error', 'message' => $bond_fetched['message']], 422);
        }

        $bond = $bond_fetched['data'];

        $cusip = $bond['cusip'];
        $name = $bond['name'];
        $coupon = (float) $bond['coupon'];
        $maturity_date = (int) $bond['maturity'];
        $issue_date = (int) $bond['issue'];

        // Calculate ROI: Simple interest from Now until Maturity
        // interest = principal * rate * (time_in_years)
        $now = now()->timestamp;
        $remaining_seconds = $maturity_date - $now;

        if ($remaining_seconds <= 0) {
            return response()->json(['status' => 'error', 'message' => __('This bond has already matured.')], 422);
        }

        $years = $remaining_seconds / (365 * 24 * 60 * 60);
        $interest_amount = $amount * ($coupon / 100) * $years;

        $user->refresh();
        $user->balance -= $total_cost;
        $user->save();
        $new_balance = $user->balance;

        $website_currency = getSetting('currency');
        $reference = \Str::orderedUuid();
        $description = 'Bond Purchase: ' . $cusip . ' (' . $name . ')';

        // Convert for recordTransaction
        $amount_usd = $total_cost;

        recordTransaction($user, $total_cost, $website_currency, $amount_usd, $website_currency, 1, "debit", "completed", $reference, $description, $new_balance);

        $holding = BondHolding::create([
            'user_id' => $user->id,
            'cusip' => $cusip,
            'bond_name' => $name,
            'amount' => $amount,
            'coupon' => $coupon,
            'interest_amount' => $interest_amount,
            'issue_date' => $issue_date,
            'maturity_date' => $maturity_date,
            'status' => 'active',
        ]);

        BondHoldingHistory::create([
            'user_id' => $user->id,
            'bond_holding_id' => $holding->id,
            'cusip' => $cusip,
            'amount' => $amount,
            'interest_amount' => $interest_amount,
            'fee_amount' => $fee_amount,
            'transaction_type' => 'buy',
        ]);

        recordNotificationMessage($user, 'Bond Investment Successful', __('Successfully invested :amount in :name. Principal and interest will be released on :date', [
            'amount' => $amount . ' ' . $website_currency,
            'name' => $name,
            'date' => date('Y-m-d', $maturity_date)
        ]));

        return response()->json([
            'status' => 'success',
            'message' => __('Bond purchased successfully'),
            'redirect' => route('user.capital-instruments.bonds')
        ], 200);
    }

    public function sellBond(Request $request, $ticker)
    {
        return response()->json(['status' => 'error', 'message' => __('Bonds cannot be sold before maturity.')], 403);
    }
}
