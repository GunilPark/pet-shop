<?php

namespace App\Filament\Resources\DogEventApplyResource\Pages;

use App\Filament\Resources\DogEventApplyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDogEventApplies extends ListRecords
{
    protected static string $resource = DogEventApplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('申請を登録'),
        ];
    }
}
