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
                    ->label('#')
                    ->sortable()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('processing_status')
                    ->label('進捗')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('ユーザー')
                    ->searchable(),

                Tables\Columns\TextColumn::make('item.name')
                    ->label('商品')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_consultation')
                    ->label('相談')
                    ->boolean(),

                Tables\Columns\TextColumn::make('consultation_status')
                    ->label('相談状態')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('決済')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\ImageColumn::make('processed_image')
                    ->label('加工画像')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('ordered_at')
                    ->label('注文日時')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('processing_status')
                    ->label('進捗ステータス')
                    ->options(ProcessingStatus::class)
                    ->multiple(),

                Tables\Filters\SelectFilter::make('order_status')
                    ->label('注文ステータス')
                    ->options(OrderStatus::class)
                    ->multiple(),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('決済ステータス')
                    ->options(PaymentStatus::class)
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('is_consultation')
                    ->label('相談あり'),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent)
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

                // ② 再加工画像送信（相談注文のみ）
                Tables\Actions\Action::make('sendPreview')
                    ->label('プレビュー送信')
                    ->icon('heroicon-o-photo')
                    ->color('info')
                    ->visible(fn (DogGoodsOrder $r) => $r->is_consultation)
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

                        try {
                            Mail::to($record->user->email)
                                ->send(new PreviewImageMail($record, $record->item, $consultation));
                        } catch (\Throwable $e) {
                            \Log::error('PreviewImageMail 送信失敗', ['order_id' => $record->id, 'error' => $e->getMessage()]);
                        }

                        Notification::make()
                            ->title('プレビューメールを送信しました')
                            ->success()
                            ->send();
                    }),

                // ③ 入金確認済み（相談なし・注文確定のみ表示）
                Tables\Actions\Action::make('confirmPayment')
                    ->label('入金確認済み')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('入金確認済みにしますか？')
                    ->modalDescription('ステータスを「加工中」に変更し、入金済みとして記録します。')
                    ->visible(fn (DogGoodsOrder $r) => ! $r->is_consultation && $r->processing_status === ProcessingStatus::Confirmed)
                    ->action(function (DogGoodsOrder $record) {
                        $record->update([
                            'processing_status' => ProcessingStatus::Processing,
                            'order_status'      => OrderStatus::Paid,
                            'payment_status'    => PaymentStatus::Paid,
                        ]);

                        Notification::make()
                            ->title('入金確認済みにしました')
                            ->success()
                            ->send();
                    }),

                // ④ 発送する（注文確定・加工中のみ表示）
                Tables\Actions\Action::make('markShipped')
                    ->label('発送する')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('発送済みに変更しますか？')
                    ->modalDescription('ステータスを「配送中」に変更します。お客様のマイページに反映されます。')
                    ->visible(fn (DogGoodsOrder $r) => in_array($r->processing_status, [ProcessingStatus::Confirmed, ProcessingStatus::Processing]))
                    ->action(function (DogGoodsOrder $record) {
                        $record->update([
                            'processing_status' => ProcessingStatus::Shipping,
                            'order_status'      => OrderStatus::Shipping,
                        ]);

                        Notification::make()
                            ->title('配送中に更新しました')
                            ->success()
                            ->send();
                    }),

                // ④ 配達完了にする（配送中のみ表示）
                Tables\Actions\Action::make('markDelivered')
                    ->label('配達完了にする')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('配達完了に変更しますか？')
                    ->modalDescription('ステータスを「配達完了」に変更します。お客様のマイページに反映されます。')
                    ->visible(fn (DogGoodsOrder $r) => $r->processing_status === ProcessingStatus::Shipping)
                    ->action(function (DogGoodsOrder $record) {
                        $record->update([
                            'processing_status' => ProcessingStatus::Delivered,
                            'order_status'      => OrderStatus::Delivered,
                        ]);

                        Notification::make()
                            ->title('配達完了に更新しました')
                            ->success()
                            ->send();
                    }),

                // ⑥ キャンセル（配送前のみ）
                Tables\Actions\Action::make('cancelOrder')
                    ->label('キャンセル')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('注文をキャンセルしますか？')
                    ->modalDescription('この操作は取り消せません。')
                    ->visible(fn (DogGoodsOrder $r) => ! in_array($r->processing_status, [ProcessingStatus::Shipping, ProcessingStatus::Delivered, ProcessingStatus::Completed, ProcessingStatus::Rejected]))
                    ->action(function (DogGoodsOrder $record) {
                        $record->update([
                            'processing_status' => ProcessingStatus::Rejected,
                            'order_status'      => OrderStatus::Canceled,
                        ]);

                        Notification::make()
                            ->title('注文をキャンセルしました')
                            ->danger()
                            ->send();
                    }),

                // ⑤ 決済メール送信（相談注文のみ・支払済以外）
                Tables\Actions\Action::make('sendPayment')
                    ->label('決済メール送信')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('顧客に決済リンクメールを送信します。送信後7日間有効です。')
                    ->visible(fn (DogGoodsOrder $r) => $r->is_consultation && $r->payment_status !== PaymentStatus::Paid)
                    ->action(function (DogGoodsOrder $record) {
                        $token = $record->generatePaymentToken();

                        try {
                            Mail::to($record->user->email)
                                ->send(new PaymentMail($record, $record->item));
                        } catch (\Throwable $e) {
                            \Log::error('PaymentMail 送信失敗', ['order_id' => $record->id, 'error' => $e->getMessage()]);
                        }

                        Notification::make()
                            ->title('決済メールを送信しました')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('processing_status', 'asc')
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
