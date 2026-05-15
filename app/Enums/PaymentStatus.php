<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PaymentStatus: string implements HasLabel, HasColor
{
    case Unsent  = 'unsent';
    case Sent    = 'sent';
    case Paid    = 'paid';
    case Expired = 'expired';

    public function getLabel(): string
    {
        return match($this) {
            self::Unsent  => '未送信',
            self::Sent    => '送信済',
            self::Paid    => '支払済',
            self::Expired => '期限切れ',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::Unsent  => 'gray',
            self::Sent    => 'warning',
            self::Paid    => 'success',
            self::Expired => 'danger',
        };
    }
}
