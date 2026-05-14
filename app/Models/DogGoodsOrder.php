<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\ProcessingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DogGoodsOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'dog_profile_id',
        'item_id',
        'order_status',
        'processing_status',
        'uploaded_image',
        'processed_image',
        'admin_memo',
        'custom_options',
        'is_consultation',
        'ordered_at',
    ];

    protected $casts = [
        'order_status'      => OrderStatus::class,
        'processing_status' => ProcessingStatus::class,
        'custom_options'    => 'array',
        'is_consultation'   => 'boolean',
        'ordered_at'        => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dogProfile(): BelongsTo
    {
        return $this->belongsTo(DogProfile::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(DogGoodsItem::class, 'item_id');
    }

    public function scopePendingProcessing($query)
    {
        return $query->where('processing_status', ProcessingStatus::Pending);
    }

    public function scopeNeedsReview($query)
    {
        return $query->where('processing_status', ProcessingStatus::Reviewing);
    }
}
