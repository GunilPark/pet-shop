<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ConsultationStatus: string implements HasLabel, HasColor
{
    case None     = 'none';
    case Waiting  = 'waiting';
    case Replied  = 'replied';
    case Resolved = 'resolved';

    public function getLabel(): string
    {
        return match($this) {
            self::None     => '—',
            self::Waiting  => '返信待ち',
            self::Replied  => '返信済',
            self::Resolved => '解決済',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::None     => 'gray',
            self::Waiting  => 'warning',
            self::Replied  => 'info',
            self::Resolved => 'success',
        };
    }
}
