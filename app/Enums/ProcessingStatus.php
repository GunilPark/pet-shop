<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProcessingStatus: string implements HasLabel, HasColor
{
    case Pending    = 'pending';
    case Reviewing  = 'reviewing';
    case Confirmed  = 'confirmed';
    case Processing = 'processing';
    case Shipping   = 'shipping';
    case Delivered  = 'delivered';
    case Completed  = 'completed';
    case Rejected   = 'rejected';

    public function getLabel(): string
    {
        return match($this) {
            self::Pending    => '未確認',
            self::Reviewing  => '確認中',
            self::Confirmed  => '注文確定',
            self::Processing => '加工中',
            self::Shipping   => '配送中',
            self::Delivered  => '配達完了',
            self::Completed  => '完了',
            self::Rejected   => '却下',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::Pending    => 'gray',
            self::Reviewing  => 'warning',
            self::Confirmed  => 'success',
            self::Processing => 'info',
            self::Shipping   => 'purple',
            self::Delivered  => 'success',
            self::Completed  => 'success',
            self::Rejected   => 'danger',
        };
    }

    public function sortOrder(): int
    {
        return match($this) {
            self::Pending    => 1,
            self::Reviewing  => 2,
            self::Confirmed  => 3,
            self::Processing => 4,
            self::Shipping   => 5,
            self::Delivered  => 6,
            self::Completed  => 7,
            self::Rejected   => 8,
        };
    }
}
