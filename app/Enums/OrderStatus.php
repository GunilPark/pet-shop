<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel, HasColor
{
    case Pending   = 'pending';
    case Paid      = 'paid';
    case Preparing = 'preparing';
    case Shipping  = 'shipping';
    case Delivered = 'delivered';
    case Canceled  = 'canceled';

    public function getLabel(): string
    {
        return match($this) {
            self::Pending   => '未払い',
            self::Paid      => '支払済',
            self::Preparing => '準備中',
            self::Shipping  => '発送中',
            self::Delivered => '配達完了',
            self::Canceled  => 'キャンセル',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::Pending   => 'warning',
            self::Paid      => 'info',
            self::Preparing => 'primary',
            self::Shipping  => 'purple',
            self::Delivered => 'success',
            self::Canceled  => 'danger',
        };
    }
}
