<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DogGoodsEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'started_at',
        'ended_at',
        'location',
        'max_capacity',
        'is_active',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'ended_at'     => 'datetime',
        'max_capacity' => 'integer',
        'is_active'    => 'boolean',
    ];

    public function applies(): HasMany
    {
        return $this->hasMany(DogEventApply::class, 'event_id');
    }

    public function approvedApplies(): HasMany
    {
        return $this->applies()->where('apply_status', 'approved');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('started_at', '>', now());
    }

    public function getRemainingCapacityAttribute(): ?int
    {
        if ($this->max_capacity === null) {
            return null;
        }

        return $this->max_capacity - $this->approvedApplies()->count();
    }

    public function isFullAttribute(): bool
    {
        if ($this->max_capacity === null) {
            return false;
        }

        return $this->remaining_capacity <= 0;
    }
}
