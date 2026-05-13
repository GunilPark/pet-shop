<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Gender: string implements HasLabel
{
    case Male    = 'male';
    case Female  = 'female';
    case Unknown = 'unknown';

    public function getLabel(): string
    {
        return match($this) {
            self::Male    => 'オス',
            self::Female  => 'メス',
            self::Unknown => '不明',
        };
    }
}
