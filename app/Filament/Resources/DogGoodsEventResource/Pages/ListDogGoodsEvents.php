<?php

namespace App\Filament\Resources\DogGoodsEventResource\Pages;

use App\Filament\Resources\DogGoodsEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDogGoodsEvents extends ListRecords
{
    protected static string $resource = DogGoodsEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('イベントを登録'),
        ];
    }
}
