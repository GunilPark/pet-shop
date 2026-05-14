<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProductType: string implements HasLabel, HasColor
{
    case Basic      = 'basic';
    case NosePrint  = 'nose_print';
    case Silhouette = 'silhouette';

    public function getLabel(): string
    {
        return match($this) {
            self::Basic      => '基本商品',
            self::NosePrint  => '鼻紋商品',
            self::Silhouette => 'シルエット商品',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::Basic      => 'gray',
            self::NosePrint  => 'info',
            self::Silhouette => 'warning',
        };
    }
}
