<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'provider_id',
        'provider_name',
        'social_token',
        'password',
        'referral_code',
        'referrer_id',
        'balance',
        'status',
        'photo',
        'lang',
        'username'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        $decimal_places = getSetting('decimal_places');
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:' . $decimal_places,
        ];
    }


    /**
     * Get the user who referred this user.
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Get the users referred by this user.
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referrer_id');
    }


    // relationship
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // define relationship to notification messages
    public function notificationMessages()
    {
        return $this->hasMany(NotificationMessage::class);
    }


    // Define relationship with onboarding
    public function onboarding()
    {
        return $this->hasOne(Onboarding::class);
    }

    // define kyc relationship
    public function kyc()
    {
        return $this->hasMany(Kyc::class);
    }

    // define relationship with the deposits
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    // relationship with investment
    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    // relationship with the investment earning
    public function investmentEarnings()
    {
        return $this->hasMany(InvestmentEarning::class);
    }

    // relationship with stock holdings
    public function stockHoldings()
    {
        return $this->hasMany(StockHolding::class);
    }

    // relationship with stock holding histories
    public function stockHoldingHistories()
    {
        return $this->hasMany(StockHoldingHistory::class);
    }

    // relationship with etf holdings
    public function etfHoldings()
    {
        return $this->hasMany(EtfHolding::class);
    }

    // relationship with etf holding histories
    public function etfHoldingHistories()
    {
        return $this->hasMany(EtfHoldingHistory::class);
    }

    // relationship with bond holdings
    public function bondHoldings()
    {
        return $this->hasMany(BondHolding::class);
    }

    // relationship with bond holding histories
    public function bondHoldingHistories()
    {
        return $this->hasMany(BondHoldingHistory::class);
    }

    // relationship with trading accounts
    public function tradingAccounts()
    {
        return $this->hasMany(TradingAccount::class);
    }

    // relationship with futures trading orders
    public function futuresTradingOrders()
    {
        return $this->hasMany(FuturesTradingOrders::class);
    }

    // relationship with futures trading positions
    public function futuresTradingPositions()
    {
        return $this->hasMany(FuturesTradingPositions::class);
    }

    // relationship with margin trading orders
    public function marginTradingOrders()
    {
        return $this->hasMany(MarginTradingOrder::class);
    }

    // relationship with margin trading positions
    public function marginTradingPositions()
    {
        return $this->hasMany(MarginTradingPosition::class);
    }

    // relationship with forex trading orders
    public function forexTradingOrders()
    {
        return $this->hasMany(ForexTradingOrder::class);
    }

    // relationship with forex trading positions
    public function forexTradingPositions()
    {
        return $this->hasMany(ForexTradingPosition::class);
    }

    // relationship with withdrawals
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include users with unverified emails.
     */
    public function scopeEmailUnverified($query)
    {
        return $query->whereNull('email_verified_at');
    }

    /**
     * Get the user's username.
     */
    public function getUsernameAttribute()
    {
        if (config('app.env') === 'sandbox' && session()->has('sandbox_user')) {
            return session()->get('sandbox_user')->first_name ?? 'User';
        }
        return $this->attributes['username'] ?? $this->attributes['first_name'] ?? 'User';
    }

    /**
     * Get the user's email.
     */
    public function getEmailAttribute()
    {
        if (config('app.env') === 'sandbox' && session()->has('sandbox_user')) {
            return session()->get('sandbox_user')->email;
        }
        return $this->attributes['email'];
    }

    /**
     * Get the user's full name.
     */
    public function getFullnameAttribute()
    {
        if (config('app.env') === 'sandbox' && session()->has('sandbox_user')) {
            return session()->get('sandbox_user')->name;
        }
        return ($this->attributes['first_name'] ?? '') . ' ' . ($this->attributes['last_name'] ?? '');
    }

    /**
     * Get the user's first name.
     */
    public function getFirstnameAttribute()
    {
        if (config('app.env') === 'sandbox' && session()->has('sandbox_user')) {
            return session()->get('sandbox_user')->first_name;
        }
        return $this->attributes['first_name'] ?? '';
    }

    /**
     * Get the user's last name.
     */
    public function getLastnameAttribute()
    {
        if (config('app.env') === 'sandbox' && session()->has('sandbox_user')) {
            return session()->get('sandbox_user')->last_name;
        }
        return $this->attributes['last_name'] ?? '';
    }
}
