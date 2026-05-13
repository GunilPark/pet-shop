<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'ユーザー管理';
    protected static ?string $modelLabel = 'ユーザー';
    protected static ?string $pluralModelLabel = 'ユーザー一覧';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('名前')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->label('メールアドレス')
                ->email()
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('password')
                ->label('パスワード')
                ->password()
                ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $context) => $context === 'create'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('名前')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('メール')
                    ->searchable(),

                Tables\Columns\TextColumn::make('dog_profiles_count')
                    ->label('犬の数')
                    ->counts('dogProfiles'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('登録日')
                    ->dateTime('Y/m/d')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\UserResource\RelationManagers\DogProfilesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
