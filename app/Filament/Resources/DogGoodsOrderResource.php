<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Enums\ProcessingStatus;
use App\Filament\Resources\DogGoodsOrderResource\Pages;
use App\Models\DogGoodsOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DogGoodsOrderResource extends Resource
{
    protected static ?string $model = DogGoodsOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'グッズ管理';
    protected static ?string $modelLabel = 'グッズ注文';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('注文情報')->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('dog_profile_id')
                    ->relationship('dogProfile', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('item_id')
                    ->relationship('item', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('order_status')
                    ->options(OrderStatus::class)
                    ->required(),

                Forms\Components\Select::make('processing_status')
                    ->options(ProcessingStatus::class)
                    ->required(),
            ])->columns(2),

            Forms\Components\Section::make('画像管理')->schema([
                Forms\Components\FileUpload::make('uploaded_image')
                    ->label('アップロード画像')
                    ->image()
                    ->directory('orders/uploaded'),

                Forms\Components\FileUpload::make('processed_image')
                    ->label('加工済画像')
                    ->image()
                    ->directory('orders/processed'),
            ])->columns(2),

            Forms\Components\Textarea::make('admin_memo')
                ->label('管理者メモ')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('ユーザー')
                    ->searchable(),

                Tables\Columns\TextColumn::make('dogProfile.name')
                    ->label('犬名')
                    ->searchable(),

                Tables\Columns\TextColumn::make('item.name')
                    ->label('商品')
                    ->searchable(),

                Tables\Columns\TextColumn::make('order_status')
                    ->badge(),

                Tables\Columns\TextColumn::make('processing_status')
                    ->badge(),

                Tables\Columns\ImageColumn::make('uploaded_image')
                    ->label('UP画像'),

                Tables\Columns\ImageColumn::make('processed_image')
                    ->label('加工画像'),

                Tables\Columns\TextColumn::make('ordered_at')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('order_status')
                    ->options(OrderStatus::class),

                Tables\Filters\SelectFilter::make('processing_status')
                    ->options(ProcessingStatus::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('startProcessing')
                    ->label('加工開始')
                    ->icon('heroicon-o-play')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (DogGoodsOrder $record) => $record->processing_status === ProcessingStatus::Reviewing)
                    ->action(fn (DogGoodsOrder $record) => $record->update([
                        'processing_status' => ProcessingStatus::Processing,
                    ])),
            ])
            ->defaultSort('ordered_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDogGoodsOrders::route('/'),
            'create' => Pages\CreateDogGoodsOrder::route('/create'),
            'edit'   => Pages\EditDogGoodsOrder::route('/{record}/edit'),
        ];
    }
}
