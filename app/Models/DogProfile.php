<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DogProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'breed',
        'birthday',
        'gender',
        'weight',
        'profile_image',
        'memo',
        'is_active',
    ];

    protected $casts = [
        'birthday'  => 'date',
        'gender'    => Gender::class,
        'weight'    => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function goodsOrders(): HasMany
    {
        return $this->hasMany(DogGoodsOrder::class);
    }

    public function eventApplies(): HasMany
    {
        return $this->hasMany(DogEventApply::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birthday?->age;
    }
}
