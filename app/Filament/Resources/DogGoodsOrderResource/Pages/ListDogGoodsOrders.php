<?php

namespace App\Filament\Resources\DogGoodsOrderResource\Pages;

use App\Filament\Resources\DogGoodsOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDogGoodsOrders extends ListRecords
{
    protected static string $resource = DogGoodsOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('注文を登録'),
        ];
    }
}
