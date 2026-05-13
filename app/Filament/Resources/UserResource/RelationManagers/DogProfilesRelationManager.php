<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enums\Gender;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DogProfilesRelationManager extends RelationManager
{
    protected static string $relationship = 'dogProfiles';
    protected static ?string $title = '犬プロフィール';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('名前')
                ->required()
                ->maxLength(100),

            Forms\Components\TextInput::make('breed')
                ->label('犬種')
                ->maxLength(100),

            Forms\Components\DatePicker::make('birthday')
                ->label('誕生日'),

            Forms\Components\Select::make('gender')
                ->label('性別')
                ->options(Gender::class)
                ->required(),

            Forms\Components\TextInput::make('weight')
                ->label('体重')
                ->numeric()
                ->suffix('kg'),

            Forms\Components\FileUpload::make('profile_image')
                ->label('プロフィール画像')
                ->image()
                ->directory('dog-profiles'),

            Forms\Components\Textarea::make('memo')
                ->label('メモ')
                ->rows(2),

            Forms\Components\Toggle::make('is_active')
                ->label('有効')
                ->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_image')
                    ->label('画像')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('名前')
                    ->searchable(),

                Tables\Columns\TextColumn::make('breed')
                    ->label('犬種'),

                Tables\Columns\TextColumn::make('gender')
                    ->label('性別')
                    ->badge(),

                Tables\Columns\TextColumn::make('birthday')
                    ->label('誕生日')
                    ->date('Y/m/d'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('有効')
                    ->boolean(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('犬を追加'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
