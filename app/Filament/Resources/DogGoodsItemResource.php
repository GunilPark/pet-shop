<?php

namespace App\Filament\Resources;

use App\Enums\ProductType;
use App\Filament\Resources\DogGoodsItemResource\Pages;
use App\Models\DogGoodsItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DogGoodsItemResource extends Resource
{
    protected static ?string $model = DogGoodsItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'グッズ管理';
    protected static ?string $modelLabel = 'グッズ商品';
    protected static ?string $pluralModelLabel = 'グッズ商品一覧';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('基本情報')->schema([
                Forms\Components\TextInput::make('name')
                    ->label('商品名')
                    ->required()
                    ->maxLength(200),

                Forms\Components\Select::make('product_type')
                    ->label('商品タイプ')
                    ->options(ProductType::class)
                    ->required()
                    ->default(ProductType::Basic)
                    ->live()
                    ->helperText(fn (Get $get) => match ($get('product_type')) {
                        'name_tag'             => '🏷️ 素材（黒メタル/木製）と刻印タイプ（鼻紋/シルエット）を注文フォームで選択できる統合ネームタグ商品',
                        'basic'                => '📦 通常の商品です。数量のみ選択して注文。',
                        default                => '',
                    }),

                Forms\Components\TextInput::make('price')
                    ->label('価格')
                    ->required()
                    ->numeric()
                    ->prefix('¥'),

                Forms\Components\TextInput::make('sort_order')
                    ->label('表示順')
                    ->numeric()
                    ->default(0),

                Forms\Components\Toggle::make('is_active')
                    ->label('公開')
                    ->default(true),
            ])->columns(2),

            Forms\Components\Section::make('商品詳細')->schema([
                Forms\Components\Textarea::make('description')
                    ->label('説明文')
                    ->rows(4)
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('thumbnail_image')
                    ->label('サムネイル画像（一覧表示用・メイン）')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('items')
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('product_images')
                    ->label('商品画像（複数枚・詳細ページに表示）')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->directory('items')
                    ->columnSpanFull(),
            ]),

            // ネームタグ共通撮影ガイド
            Forms\Components\Section::make('📷 ネームタグ — 撮影ガイド文')
                ->schema([
                    Forms\Components\Textarea::make('nose_print_guide')
                        ->label('鼻紋撮影ガイド（注文フォームに表示）')
                        ->placeholder("例：鼻の正面から、しっかりピントを合わせて撮影してください。\nフラッシュなしで明るい場所での撮影を推奨します。")
                        ->rows(3)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('silhouette_guide')
                        ->label('シルエット撮影ガイド（注文フォームに表示）')
                        ->placeholder("例：横向きで全身が写るように撮影してください。\n背景はシンプルな方が仕上がりがきれいになります。")
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->visible(fn (Get $get) => $get('product_type') === 'name_tag')
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_image')
                    ->label('画像'),

                Tables\Columns\TextColumn::make('name')
                    ->label('商品名')
                    ->searchable(),

                Tables\Columns\TextColumn::make('product_type')
                    ->label('タイプ')
                    ->badge(),

                Tables\Columns\TextColumn::make('price')
                    ->label('価格')
                    ->money('JPY')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('表示順')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('公開')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_type')
                    ->label('タイプ')
                    ->options(ProductType::class),
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDogGoodsItems::route('/'),
            'create' => Pages\CreateDogGoodsItem::route('/create'),
            'edit'   => Pages\EditDogGoodsItem::route('/{record}/edit'),
        ];
    }
}
