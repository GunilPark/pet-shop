<?php

namespace App\Models;

use App\Enums\ConsultationStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProcessingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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
        'payment_token',
        'payment_status',
        'consultation_status',
        'preview_sent_at',
        'payment_sent_at',
    ];

    protected $casts = [
        'order_status'        => OrderStatus::class,
        'processing_status'   => ProcessingStatus::class,
        'payment_status'      => PaymentStatus::class,
        'consultation_status' => ConsultationStatus::class,
        'custom_options'      => 'array',
        'is_consultation'     => 'boolean',
        'ordered_at'          => 'datetime',
        'preview_sent_at'     => 'datetime',
        'payment_sent_at'     => 'datetime',
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

    public function consultations(): HasMany
    {
        return $this->hasMany(DogGoodsConsultation::class, 'order_id');
    }

    public function generatePaymentToken(): string
    {
        $token = Str::random(64);
        $this->update([
            'payment_token'  => $token,
            'payment_status' => PaymentStatus::Sent,
            'payment_sent_at' => now(),
        ]);
        return $token;
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
