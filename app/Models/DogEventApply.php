<?php

namespace App\Models;

use App\Enums\ApplyStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DogEventApply extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'user_id',
        'dog_profile_id',
        'apply_status',
        'applied_at',
    ];

    protected $casts = [
        'apply_status' => ApplyStatus::class,
        'applied_at'   => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(DogGoodsEvent::class, 'event_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dogProfile(): BelongsTo
    {
        return $this->belongsTo(DogProfile::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('apply_status', ApplyStatus::Approved);
    }

    public function scopePending($query)
    {
        return $query->where('apply_status', ApplyStatus::Applied);
    }
}
