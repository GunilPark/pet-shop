<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ApplyStatus: string implements HasLabel, HasColor
{
    case Applied  = 'applied';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Canceled = 'canceled';

    public function getLabel(): string
    {
        return match($this) {
            self::Applied  => '申請中',
            self::Approved => '承認済',
            self::Rejected => '却下',
            self::Canceled => 'キャンセル',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::Applied  => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
            self::Canceled => 'gray',
        };
    }
}
