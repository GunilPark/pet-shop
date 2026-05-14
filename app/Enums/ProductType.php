<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProductType: string implements HasLabel, HasColor
{
    case Basic              = 'basic';
    case NameTag            = 'name_tag';             // ネームタグ（素材・刻印タイプは注文フォームで選択）
    case NosePrintTag       = 'nose_print_tag';       // 旧: 鼻紋 軍番タグ
    case SilhouetteTag      = 'silhouette_tag';       // 旧: シルエット 軍番タグ
    case SilhouetteKeychain = 'silhouette_keychain';  // 旧: シルエット 木製キーホルダー

    public function getLabel(): string
    {
        return match($this) {
            self::Basic              => '基本商品',
            self::NameTag            => 'ネームタグ',
            self::NosePrintTag       => '鼻紋ネームタグ（軍番タグ）',
            self::SilhouetteTag      => 'シルエットネームタグ（軍番タグ）',
            self::SilhouetteKeychain => 'シルエットキーホルダー（木製）',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::Basic              => 'gray',
            self::NameTag            => 'warning',
            self::NosePrintTag       => 'info',
            self::SilhouetteTag      => 'warning',
            self::SilhouetteKeychain => 'success',
        };
    }

    public function requiresPhoto(): bool
    {
        return in_array($this, [
            self::NameTag,
            self::NosePrintTag,
            self::SilhouetteTag,
            self::SilhouetteKeychain,
        ]);
    }

    public function requiresDogProfile(): bool
    {
        return false;
    }
}
