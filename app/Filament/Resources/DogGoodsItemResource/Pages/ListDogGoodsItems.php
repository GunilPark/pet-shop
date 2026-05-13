<?php

namespace App\Filament\Resources\DogGoodsItemResource\Pages;

use App\Filament\Resources\DogGoodsItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDogGoodsItems extends ListRecords
{
    protected static string $resource = DogGoodsItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('商品を登録'),
        ];
    }
}
