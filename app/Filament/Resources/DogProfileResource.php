<?php

namespace App\Filament\Resources;

use App\Enums\Gender;
use App\Filament\Resources\DogProfileResource\Pages;
use App\Models\DogProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DogProfileResource extends Resource
{
    protected static ?string $model = DogProfile::class;
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationGroup = 'ユーザー管理';
    protected static ?string $modelLabel = '犬プロフィール';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(100),

            Forms\Components\TextInput::make('breed')
                ->maxLength(100),

            Forms\Components\DatePicker::make('birthday'),

            Forms\Components\Select::make('gender')
                ->options(Gender::class)
                ->required(),

            Forms\Components\TextInput::make('weight')
                ->numeric()
                ->suffix('kg'),

            Forms\Components\FileUpload::make('profile_image')
                ->image()
                ->directory('dog-profiles'),

            Forms\Components\Textarea::make('memo')
                ->rows(3),

            Forms\Components\Toggle::make('is_active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_image')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('オーナー')
                    ->searchable(),

                Tables\Columns\TextColumn::make('breed')
                    ->searchable(),

                Tables\Columns\TextColumn::make('gender')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y/m/d')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDogProfiles::route('/'),
            'create' => Pages\CreateDogProfile::route('/create'),
            'edit'   => Pages\EditDogProfile::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->withTrashed();
    }
}
