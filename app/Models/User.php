<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'postal_code',
        'prefecture',
        'city',
        'address_line',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function dogProfiles(): HasMany
    {
        return $this->hasMany(DogProfile::class);
    }

    public function goodsOrders(): HasMany
    {
        return $this->hasMany(DogGoodsOrder::class);
    }

    public function eventApplies(): HasMany
    {
        return $this->hasMany(DogEventApply::class);
    }
}
