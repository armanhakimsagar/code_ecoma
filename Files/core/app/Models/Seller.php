<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\SellerNotify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Seller extends Authenticatable
{
    use HasFactory, SellerNotify;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'ver_code_send_at' => 'datetime',
        'kyc_data' => 'object'
    ];

    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn() => $this->firstname . ' ' . $this->lastname,
        );
    }

    public function mobileNumber(): Attribute
    {
        return new Attribute(
            get: fn() => $this->dial_code . $this->mobile,
        );
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    public function loginLogs()
    {
        return $this->hasMany(UserLogin::class, 'seller_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'seller_id')->orderBy('id', 'desc');
    }
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'seller_id')->where('status', '!=', 0);
    }

    public function scopeActive($query)
    {
        return $query->where('status', Status::USER_ACTIVE)->where('ev', Status::VERIFIED)->where('sv', Status::VERIFIED);
    }
    public function scopeBanned($query)
    {
        return $query->where('status', Status::USER_BAN);
    }
    public function scopeFeatured($query)
    {
        return $query->where('featured', Status::YES);
    }

    public function scopeEmailUnverified($query)
    {
        return $query->where('ev', Status::UNVERIFIED);
    }

    public function scopeMobileUnverified($query)
    {
        return $query->where('sv', Status::UNVERIFIED);
    }
    public function scopeEmailVerified($query)
    {
        return $query->where('ev', Status::VERIFIED);
    }

    public function scopeMobileVerified($query)
    {
        return $query->where('sv', Status::VERIFIED);
    }

    public function scopeKycPending($query)
    {
        return $query->where('kv', Status::KYC_PENDING);
    }

    public function scopeKycUnverified($query)
    {
        return $query->where('kv', Status::KYC_UNVERIFIED);
    }

    public function scopeWithBalance($query)
    {
        return $query->where('balance', '>', 0);
    }

    public function totalSold()
    {
        return $this->products()->sum('sold');
    }
}
