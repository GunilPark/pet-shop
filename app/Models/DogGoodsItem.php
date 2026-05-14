<?php

namespace App\Models;

use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DogGoodsItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'product_type',
        'price',
        'description',
        'thumbnail_image',
        'nose_print_guide',
        'silhouette_guide',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'product_type' => ProductType::class,
        'price'        => 'integer',
        'is_active'    => 'boolean',
        'sort_order'   => 'integer',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(DogGoodsOrder::class, 'item_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
