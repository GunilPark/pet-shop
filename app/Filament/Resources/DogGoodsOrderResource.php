<?php

namespace App\Filament\Resources;

use App\Enums\ConsultationStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProcessingStatus;
use App\Filament\Resources\DogGoodsOrderResource\Pages;
use App\Mail\ConsultationMail;
use App\Mail\PaymentMail;
use App\Mail\PreviewImageMail;
use App\Models\DogGoodsConsultation;
use App\Models\DogGoodsOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DogGoodsOrderResource extends Resource
{
    protected static ?string $model = DogGoodsOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'グッズ管理';
    protected static ?string $modelLabel = 'グッズ注文';
    protected static ?string $pluralModelLabel = 'グッズ注文一覧';
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
                    ->searchable(),

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

            Forms\Components\Section::make('ステータス管理')->schema([
                Forms\Components\Select::make('consultation_status')
                    ->label('相談ステータス')
                    ->options(ConsultationStatus::class),

                Forms\Components\Select::make('payment_status')
                    ->label('決済ステータス')
                    ->options(PaymentStatus::class),

                Forms\Components\DateTimePicker::make('preview_sent_at')
                    ->label('プレビュー送信日時')
                    ->displayFormat('Y/m/d H:i'),

                Forms\Components\DateTimePicker::make('payment_sent_at')
                    ->label('決済メール送信日時')
                    ->displayFormat('Y/m/d H:i'),
            ])->columns(2),

            Forms\Components\Section::make('画像管理')->schema([
                Forms\Components\FileUpload::make('uploaded_image')
                    ->label('アップロード画像')
                    ->disk('public')
                    ->image()
                    ->directory('orders/uploaded'),

                Forms\Components\FileUpload::make('processed_image')
                    ->label('加工済画像')
                    ->disk('public')
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

                Tables\Columns\TextColumn::make('item.name')
                    ->label('商品')
                    ->searchable(),

                Tables\Columns\TextColumn::make('order_status')
                    ->badge(),

                Tables\Columns\TextColumn::make('processing_status')
                    ->badge(),

                Tables\Columns\TextColumn::make('consultation_status')
                    ->label('相談')
                    ->badge(),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('決済')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_consultation')
                    ->label('相談')
                    ->boolean(),

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

                Tables\Filters\SelectFilter::make('payment_status')
                    ->options(PaymentStatus::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                // ① 加工開始
                Tables\Actions\Action::make('startProcessing')
                    ->label('加工開始')
                    ->icon('heroicon-o-play')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (DogGoodsOrder $r) => $r->processing_status === ProcessingStatus::Reviewing)
                    ->action(fn (DogGoodsOrder $r) => $r->update(['processing_status' => ProcessingStatus::Processing])),

                // ② 再加工画像送信
                Tables\Actions\Action::make('sendPreview')
                    ->label('プレビュー送信')
                    ->icon('heroicon-o-photo')
                    ->color('info')
                    ->form([
                        Forms\Components\FileUpload::make('preview_image')
                            ->label('加工プレビュー画像')
                            ->disk('public')
                            ->directory('orders/processed')
                            ->image()
                            ->required(),
                        Forms\Components\Textarea::make('reply_message')
                            ->label('担当者コメント')
                            ->rows(4)
                            ->placeholder('加工についてのコメントをご記入ください'),
                    ])
                    ->action(function (DogGoodsOrder $record, array $data) {
                        $consultation = DogGoodsConsultation::create([
                            'order_id'      => $record->id,
                            'admin_id'      => Auth::id(),
                            'reply_message' => $data['reply_message'] ?? null,
                            'preview_image' => $data['preview_image'],
                            'status'        => 'replied',
                            'sent_at'       => now(),
                        ]);

                        $record->update([
                            'processed_image'     => $data['preview_image'],
                            'consultation_status' => ConsultationStatus::Replied,
                            'preview_sent_at'     => now(),
                        ]);

                        Mail::to($record->user->email)
                            ->send(new PreviewImageMail($record, $record->item, $consultation));

                        Notification::make()
                            ->title('プレビューメールを送信しました')
                            ->success()
                            ->send();
                    }),

                // ③ 決済メール送信
                Tables\Actions\Action::make('sendPayment')
                    ->label('決済メール送信')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('顧客に決済リンクメールを送信します。送信後7日間有効です。')
                    ->visible(fn (DogGoodsOrder $r) => $r->payment_status !== PaymentStatus::Paid)
                    ->action(function (DogGoodsOrder $record) {
                        $token = $record->generatePaymentToken();

                        Mail::to($record->user->email)
                            ->send(new PaymentMail($record, $record->item));

                        Notification::make()
                            ->title('決済メールを送信しました')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('ordered_at', 'desc');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDogGoodsOrders::route('/'),
            'edit'  => Pages\EditDogGoodsOrder::route('/{record}/edit'),
        ];
    }
}
