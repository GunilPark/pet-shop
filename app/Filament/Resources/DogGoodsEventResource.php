<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DogGoodsEventResource\Pages;
use App\Models\DogGoodsEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DogGoodsEventResource extends Resource
{
    protected static ?string $model = DogGoodsEvent::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'イベント管理';
    protected static ?string $modelLabel = 'イベント';
    protected static ?string $pluralModelLabel = 'イベント一覧';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(200)
                ->columnSpanFull(),

            Forms\Components\Textarea::make('description')
                ->rows(4)
                ->columnSpanFull(),

            Forms\Components\DateTimePicker::make('started_at')
                ->required(),

            Forms\Components\DateTimePicker::make('ended_at')
                ->required()
                ->after('started_at'),

            Forms\Components\TextInput::make('location')
                ->maxLength(200),

            Forms\Components\TextInput::make('max_capacity')
                ->numeric()
                ->nullable()
                ->helperText('空欄で定員なし'),

            Forms\Components\Toggle::make('is_active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('started_at')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ended_at')
                    ->dateTime('Y/m/d H:i'),

                Tables\Columns\TextColumn::make('location'),

                Tables\Columns\TextColumn::make('max_capacity')
                    ->label('定員'),

                Tables\Columns\TextColumn::make('applies_count')
                    ->label('申請数')
                    ->counts('applies'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('started_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDogGoodsEvents::route('/'),
            'create' => Pages\CreateDogGoodsEvent::route('/create'),
            'edit'   => Pages\EditDogGoodsEvent::route('/{record}/edit'),
        ];
    }
}
