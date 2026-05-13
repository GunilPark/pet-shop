<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProcessingStatus: string implements HasLabel, HasColor
{
    case Pending    = 'pending';
    case Reviewing  = 'reviewing';
    case Processing = 'processing';
    case Completed  = 'completed';
    case Rejected   = 'rejected';

    public function getLabel(): string
    {
        return match($this) {
            self::Pending    => '未確認',
            self::Reviewing  => '確認中',
            self::Processing => '加工中',
            self::Completed  => '完了',
            self::Rejected   => '却下',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::Pending    => 'gray',
            self::Reviewing  => 'warning',
            self::Processing => 'info',
            self::Completed  => 'success',
            self::Rejected   => 'danger',
        };
    }
}
