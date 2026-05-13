<?php

namespace App\Filament\Resources;

use App\Enums\ApplyStatus;
use App\Filament\Resources\DogEventApplyResource\Pages;
use App\Models\DogEventApply;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DogEventApplyResource extends Resource
{
    protected static ?string $model = DogEventApply::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'イベント管理';
    protected static ?string $modelLabel = 'イベント申請';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('event_id')
                ->relationship('event', 'title')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('dog_profile_id')
                ->relationship('dogProfile', 'name')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('apply_status')
                ->options(ApplyStatus::class)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.title')
                    ->label('イベント')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('申請者')
                    ->searchable(),

                Tables\Columns\TextColumn::make('dogProfile.name')
                    ->label('犬名'),

                Tables\Columns\TextColumn::make('apply_status')
                    ->badge(),

                Tables\Columns\TextColumn::make('applied_at')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('apply_status')
                    ->options(ApplyStatus::class),

                Tables\Filters\SelectFilter::make('event')
                    ->relationship('event', 'title'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('承認')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->requiresConfirmation()
                    ->visible(fn (DogEventApply $r) => $r->apply_status === ApplyStatus::Applied)
                    ->action(fn (DogEventApply $r) => $r->update(['apply_status' => ApplyStatus::Approved])),

                Tables\Actions\Action::make('reject')
                    ->label('却下')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->requiresConfirmation()
                    ->visible(fn (DogEventApply $r) => $r->apply_status === ApplyStatus::Applied)
                    ->action(fn (DogEventApply $r) => $r->update(['apply_status' => ApplyStatus::Rejected])),

                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('applied_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDogEventApplies::route('/'),
            'create' => Pages\CreateDogEventApply::route('/create'),
            'edit'   => Pages\EditDogEventApply::route('/{record}/edit'),
        ];
    }
}
