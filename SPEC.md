# INU GOODS — システム仕様書

> 作成日: 2026-05-15 / 最終更新: 2026-05-15
> フレームワーク: Laravel 11 + Filament PHP v3
> 環境: MAMP (PHP 8.2 / MySQL / Apache)

---

## 1. システム概要

愛犬向けオーダーメイドグッズ販売・イベント管理プラットフォーム。
管理者はFilament管理画面で商品・イベント・注文・ユーザーを管理し、
顧客はフロントエンドから会員登録・グッズ購入・イベント参加申請を行う。

### 主な機能
| 機能 | 説明 |
|------|------|
| 会員登録・ログイン | Laravel Breeze（メール／パスワード） |
| 配送先住所管理 | マイページで住所登録・注文時に連携 or 直接入力 |
| 犬プロフィール管理 | 1ユーザー複数頭登録可・注文時にペット選択 |
| グッズ購入フロー | フォーム入力 → プレビュー → 購入確定 or メール相談 |
| 写真加工プレビュー | GDライブラリでエングレービング風処理（グレースケール＋反転） |
| カメラ撮影 | ブラウザカメラ起動・SVGガイド枠付き撮影（鼻紋/シルエット） |
| イベント参加申請 | 定員チェック・重複チェック付き |
| 管理画面 | Filament v3（ユーザー・商品・注文・イベント管理） |
| メールフロー | 新規注文／相談申請／プレビュー送信／決済案内（MailHog対応） |
| 決済ページ | トークン認証・ログイン不要（決済処理は未実装） |
| 注文ステータス管理 | 管理者がフロー別にステータスをボタン操作で更新 |
| 注文キャンセル | 管理者・ユーザー双方からキャンセル可能（配送前のみ） |

---

## 2. 技術スタック

| 項目 | 内容 |
|------|------|
| PHP | 8.2（composer platform固定） |
| Framework | Laravel 11 |
| Admin Panel | Filament PHP v3 |
| Auth | Laravel Breeze（Blade） |
| Frontend CSS | Tailwind CSS CDN |
| Frontend JS | Alpine.js v3 CDN |
| DB | MySQL（MAMP、port: 8889） |
| Storage | Local Public Disk（`storage/app/public`） |
| 画像処理 | PHP GD ライブラリ |
| メール（開発） | MailHog（SMTP localhost:1025 / UI: localhost:8025） |
| URL | http://localhost:8888 |
| Admin URL | http://localhost:8888/admin |

---

## 3. データベース設計

### 3-1. users
| カラム | 型 | 備考 |
|--------|-----|------|
| id | bigint | PK |
| name | string | |
| email | string | unique |
| email_verified_at | timestamp | nullable |
| password | string | hashed |
| postal_code | string(8) | nullable |
| prefecture | string(20) | nullable |
| city | string(100) | nullable |
| address_line | string(200) | nullable |
| phone | string(20) | nullable |
| remember_token | string | nullable |
| created_at / updated_at | timestamp | |

---

### 3-2. dog_profiles
| カラム | 型 | 備考 |
|--------|-----|------|
| id | bigint | PK |
| user_id | FK → users | cascade delete |
| name | string | |
| breed | string | nullable |
| birthday | date | nullable |
| gender | enum | male / female / unknown |
| weight | decimal(5,2) | nullable（kg） |
| profile_image | string | nullable（storage path） |
| memo | text | nullable |
| is_active | boolean | default: true |
| deleted_at | timestamp | SoftDelete |

---

### 3-3. dog_goods_items（グッズ商品）
| カラム | 型 | 備考 |
|--------|-----|------|
| id | bigint | PK |
| name | string | |
| product_type | enum | basic / name_tag / nose_print_tag / silhouette_tag / silhouette_keychain |
| price | int unsigned | |
| description | text | nullable |
| thumbnail_image | string | nullable（一覧表示用） |
| product_images | json | nullable（複数画像配列） |
| nose_print_guide | text | nullable（鼻紋撮影ガイド文） |
| silhouette_guide | text | nullable（シルエット撮影ガイド文） |
| is_active | boolean | default: true |
| sort_order | smallint | default: 0 |
| deleted_at | timestamp | SoftDelete |

---

### 3-4. dog_goods_orders（グッズ注文）
| カラム | 型 | 備考 |
|--------|-----|------|
| id | bigint | PK |
| user_id | FK → users | cascade delete |
| dog_profile_id | FK → dog_profiles | nullable / restrict |
| item_id | FK → dog_goods_items | restrict |
| quantity | smallint unsigned | default: 1 |
| shipping_name | string | nullable（宛名） |
| postal_code | string(10) | nullable（インデックスあり） |
| prefecture | string(20) | nullable |
| city | string(100) | nullable |
| address_line | string(200) | nullable |
| phone | string(20) | nullable |
| order_status | enum | pending / paid / preparing / shipping / delivered / canceled |
| processing_status | enum | pending / reviewing / confirmed / processing / shipping / delivered / completed / rejected |
| consultation_status | enum | none / waiting / replied / resolved |
| payment_status | enum | unsent / sent / paid / expired |
| uploaded_image | string | nullable（アップロード画像path） |
| processed_image | string | nullable（加工済画像path） |
| custom_options | json | nullable（商品オプションのみ。住所・数量は専用カラムへ） |
| is_consultation | boolean | default: false（メール相談フラグ） |
| admin_memo | text | nullable |
| payment_token | string(64) | nullable / unique（決済URL用） |
| preview_sent_at | timestamp | nullable |
| payment_sent_at | timestamp | nullable |
| ordered_at | timestamp | |
| deleted_at | timestamp | SoftDelete |

#### custom_options の構造（JSON）※商品オプションのみ
```json
{
  "material": "black|wood",
  "engraving_type": "nose_print|silhouette",
  "name": "ペット名",
  "breed": "犬種",
  "birthday": "YYYY.MM.DD",
  "message": "裏面メッセージ",
  "dog_profile_id": null,
  "temp_image": "orders/temp/eng_xxx.jpg"
}
```

---

### 3-5. dog_goods_consultations（相談管理）
| カラム | 型 | 備考 |
|--------|-----|------|
| id | bigint | PK |
| order_id | FK → dog_goods_orders | cascade delete |
| admin_id | FK → users | restrict |
| message | text | nullable（相談内容） |
| reply_message | text | nullable（担当者コメント） |
| preview_image | string | nullable（加工プレビュー画像path） |
| status | enum | pending / replied / resolved |
| sent_at | timestamp | nullable |
| created_at / updated_at | timestamp | |

---

### 3-6. dog_goods_events（イベント）
| カラム | 型 | 備考 |
|--------|-----|------|
| id | bigint | PK |
| title | string | |
| description | text | nullable |
| started_at | datetime | |
| ended_at | datetime | |
| location | string | nullable |
| max_capacity | smallint | nullable（nullで定員なし） |
| is_active | boolean | default: true |
| deleted_at | timestamp | SoftDelete |

---

### 3-7. dog_event_applies（イベント参加申請）
| カラム | 型 | 備考 |
|--------|-----|------|
| id | bigint | PK |
| event_id | FK → dog_goods_events | cascade delete |
| user_id | FK → users | cascade delete |
| dog_profile_id | FK → dog_profiles | restrict |
| apply_status | enum | applied / approved / rejected / canceled |
| applied_at | timestamp | |
| deleted_at | timestamp | SoftDelete |
| | | Unique: [event_id, dog_profile_id] |

---

## 4. Enum 定義

### ProductType
| Case | Value | ラベル |
|------|-------|--------|
| Basic | basic | 基本商品 |
| NameTag | name_tag | ネームタグ |
| NosePrintTag | nose_print_tag | 鼻紋ネームタグ（旧型） |
| SilhouetteTag | silhouette_tag | シルエットネームタグ（旧型） |
| SilhouetteKeychain | silhouette_keychain | シルエットキーホルダー（旧型） |

### OrderStatus
`pending`（未払い）→ `paid`（支払済）→ `shipping`（発送中）→ `delivered`（配達完了）
または `canceled`（キャンセル）

### ProcessingStatus（進捗ステータス）

**相談なしフロー：**
```
confirmed（注文確定）→ [入金確認済みボタン] → processing（加工中）→ [発送ボタン] → shipping（配送中）→ [配達完了ボタン] → delivered（配達完了）
```

**相談ありフロー：**
```
reviewing（確認中）→ [加工開始ボタン] → processing（加工中）→ [発送ボタン] → shipping（配送中）→ [配達完了ボタン] → delivered（配達完了）
```

| Value | ラベル | マイページ表示 |
|-------|--------|--------------|
| pending | 未確認 | 受付中 |
| reviewing | 確認中 | 確認中 |
| confirmed | 注文確定 | 注文確定 |
| processing | 加工中 | 制作中 |
| shipping | 配送中 | 配送中 |
| delivered | 配達完了 | 配達完了 ✓ |
| completed | 完了 | 完了 |
| rejected | 却下 | キャンセル |

### ConsultationStatus
`none` → `waiting` → `replied` → `resolved`

### PaymentStatus
`unsent`（未送信）→ `sent`（送信済）→ `paid`（支払済）または `expired`（期限切れ）

### ApplyStatus
`applied` → `approved`（または `rejected` / `canceled`）

### Gender
`male`（オス）/ `female`（メス）/ `unknown`（不明）

---

## 5. 画面一覧

### フロントエンド（顧客向け）
| URL | ビュー | 説明 |
|-----|--------|------|
| `/` | welcome.blade.php | トップページ（最新商品・イベント表示） |
| `/goods` | goods.blade.php | グッズ一覧（サムネイル・商品名・価格） |
| `/goods/{item}/order` | goods/order-name-tag.blade.php | 注文入力フォーム（STEP1〜6） |
| `/goods/{item}/preview` | goods/order-preview.blade.php | 注文確認・プレビュー |
| `/event` | event.blade.php | イベント一覧 |
| `/event/{event}/apply` | event/apply.blade.php | イベント参加申請フォーム |
| `/mypage` | mypage.blade.php | マイページ（住所管理・注文・申請履歴・キャンセル） |
| `/mypage/address` | — | 住所更新（PATCH） |
| `/mypage/orders/{order}/cancel` | — | 注文キャンセル（DELETE） |
| `/dog-profile/create` | dog-profile/create.blade.php | 犬プロフィール登録 |
| `/dog-profile/{id}/edit` | dog-profile/edit.blade.php | 犬プロフィール編集 |
| `/payment/{token}` | payment/show.blade.php | 決済ページ（ログイン不要） |

### 管理画面（Filament）
| URL | 説明 |
|-----|------|
| `/admin` | ダッシュボード |
| `/admin/users` | ユーザー管理（犬プロフィールRelationManager内包） |
| `/admin/dog-goods-items` | グッズ商品管理（複数画像・ガイド文管理） |
| `/admin/dog-goods-orders` | 注文管理（各種ステータス操作アクション） |
| `/admin/dog-goods-events` | イベント管理 |
| `/admin/dog-event-applies` | 参加申請管理 |

---

## 6. 注文フロー

### 6-1. 通常購入フロー
```
顧客
 │
 ├─ /goods                         商品一覧
 │
 ├─ /goods/{item}/order            注文入力フォーム (GET)
 │    ├─ STEP1: 素材選択（黒プラスチック / 木製）
 │    ├─ STEP2: 刻印タイプ（鼻紋 / シルエット）
 │    ├─ STEP3: 写真（カメラ撮影 or ファイル選択）
 │    ├─ STEP4: ペット選択（登録済みペット or 直接入力）
 │    ├─ STEP5: 名前・犬種・誕生日・メッセージ
 │    └─ STEP6: 配送先住所（マイページ連携 or 直接入力）
 │
 ├─ POST /goods/{item}/preview     バリデーション＋画像処理
 │    ├─ PHP GD で画像加工
 │    └─ セッションに保存
 │
 ├─ /goods/{item}/preview 確認画面
 │    ├─ タグプレビュー表示（表面・裏面CSS）
 │    ├─ 配送先住所確認
 │    └─ ボタン：「購入確定」or「メール相談」or「修正する」
 │
 └─ POST /goods/{item}/order       注文保存
      ├─ DogGoodsOrder 作成（processing_status = confirmed）
      ├─ 管理者へ新規注文通知メール（NewOrderAdminMail）
      └─ ユーザーへ受付完了メール（NewOrderUserMail）
```

### 6-2. メール相談フロー（is_consultation=true）
```
顧客が「メールで相談する」を選択
 │
 ├─ 注文登録（is_consultation=true, processing_status = reviewing）
 ├─ 管理者へ相談申請メール（ConsultationMail）
 │
 └─ 管理画面（Filament）
      ├─ 「加工開始」アクション（reviewing → processing）
      │
      ├─ 「プレビュー送信」アクション
      │    ├─ 加工画像アップロード
      │    ├─ 担当者コメント入力
      │    ├─ DogGoodsConsultation 作成
      │    └─ ユーザーへプレビューメール（PreviewImageMail）
      │
      └─ 「決済メール送信」アクション
           ├─ payment_token 生成（64文字ランダム）
           ├─ payment_sent_at 記録
           └─ ユーザーへ決済案内メール（PaymentMail）
                └─ /payment/{token} リンク（7日間有効）
```

---

## 7. 画像処理仕様

### カメラ撮影（フロントエンド）
- `getUserMedia({ video: true })` でカメラ起動
- SVGオーバーレイでガイド枠を表示（`x-show` でタイプ切替）
  - 鼻紋: 楕円（cx:50%, cy:50%, rx:35%, ry:28%）＋中央十字線
  - シルエット: 矩形（x:10%, y:15%, w:80%, h:70%）
- Canvas で枠外を黒塗り（evenodd fill）→ base64で送信

### サーバーサイド処理（PHP GD）
1. **リサイズ**：最大800px（処理高速化）
2. **ガイドマスク適用**（ファイルアップロード時のみ）：枠外ピクセルを黒に
3. **グレースケール**：`IMG_FILTER_GRAYSCALE`
4. **コントラスト強調**：`IMG_FILTER_CONTRAST, -70`
5. **色反転**：`IMG_FILTER_NEGATE`（黒い被写体→白い刻印風）
6. **背景除去**：輝度閾値（鼻紋:55 / シルエット:45）以下のピクセルを純黒に
7. **スムーズ**：`IMG_FILTER_SMOOTH, 1`
8. 保存先：`storage/app/public/orders/temp/eng_{uniqid}.jpg`

---

## 8. メールフロー仕様

### メール一覧
| Mailable | テンプレート | 送信先 | タイミング |
|----------|-------------|--------|-----------|
| NewOrderAdminMail | emails/new-order-admin | 管理者 | 注文確定時 |
| NewOrderUserMail | emails/new-order-user | 顧客 | 注文確定時 |
| ConsultationMail | emails/consultation | 管理者 | 相談申請時 |
| PreviewImageMail | emails/preview-image | 顧客 | 管理者がプレビュー送信時 |
| PaymentMail | emails/payment | 顧客 | 管理者が決済メール送信時 |

### メール共通構成
- 共通HTMLレイアウト：`resources/views/emails/layouts/base.blade.php`
- 注文情報共通パーツ：`resources/views/emails/partials/order-info.blade.php`
- 開発環境：MailHog（localhost:1025 / UI: localhost:8025）
- エラーハンドリング：try-catch でラップ、失敗時は `Log::error` に記録（注文処理は続行）

### 決済メール仕様
- トークン：64文字ランダム文字列（`Str::random(64)`）
- 有効期限：送信から7日間
- URL形式：`/payment/{token}`（ログイン不要）
- 期限切れ・支払済はそれぞれ専用ページを表示

---

## 9. Filament 管理画面構成

### ナビゲーショングループ
| グループ | リソース |
|----------|----------|
| ユーザー管理 | UserResource（犬プロフィールRelationManager内包） |
| グッズ管理 | DogGoodsItemResource, DogGoodsOrderResource |
| イベント管理 | DogGoodsEventResource, DogEventApplyResource |

### DogGoodsOrderResource アクション
| アクション | 表示条件 | 内容 |
|-----------|----------|------|
| 加工開始 | processing_status = reviewing | reviewing → processing |
| 入金確認済み | 相談なし かつ confirmed | payment_status=Paid / order_status=Paid / processing_status=Processing |
| プレビュー送信 | is_consultation = true | 画像＋コメント入力 → PreviewImageMail 送信 |
| 発送する | confirmed または processing | processing_status=Shipping / order_status=Shipping |
| 配達完了にする | shipping | processing_status=Delivered / order_status=Delivered |
| 決済メール送信 | is_consultation かつ 未支払 | PaymentMail 送信・トークン生成 |
| キャンセル | 配送前（shipping/delivered/completed/rejected 以外） | processing_status=Rejected / order_status=Canceled |

### フィルター・ソート
- 進捗・注文・決済ステータス（複数選択）・相談ありフィルターをテーブル上部に常時表示
- デフォルトソート：processing_status 順 → ordered_at 降順

---

## 10. 認証・権限

| 種別 | 実装 |
|------|------|
| 顧客認証 | Laravel Breeze（セッション） |
| 管理者認証 | Filament 独自（`php artisan make:filament-user`） |
| 犬プロフィール保護 | `abort_if($dogProfile->user_id !== auth()->id(), 403)` |
| 注文フォーム保護 | `auth` ミドルウェア |
| 注文キャンセル保護 | `abort_if($order->user_id !== auth()->id(), 403)` |
| 決済ページ | トークン認証のみ（ログイン不要） |

---

## 11. ファイルストレージ

| 用途 | パス | disk |
|------|------|------|
| 商品サムネイル | `items/{filename}` | public |
| 商品複数画像 | `items/{filename}` | public |
| 犬プロフィール画像 | `dog-profiles/{filename}` | public |
| 注文一時画像 | `orders/temp/eng_{id}.jpg` | public |
| 注文確定画像 | `orders/uploaded/{filename}` | public |
| 加工済画像 | `orders/processed/{filename}` | public |

シンボリックリンク：`public/storage` → `storage/app/public`
（`php artisan storage:link` で作成済み）

---

## 12. 残タスク・今後の拡張予定

| # | 機能 | 優先度 | 状態 |
|---|------|--------|------|
| 1 | AI API連携プレビュー生成 | 高 | 未実装（現在はPHP GDで近似処理） |
| 2 | 決済連携（Stripe等） | 高 | 未実装（/payment/{token} ページのみ実装済み） |
| 3 | 入力バリデーション強化 | 中 | 未実装（下記メモ参照） |
| 4 | セキュリティー対策 | 中 | 未実装（下記メモ参照） |
| 5 | 全体テスト | 中 | 未実施 |
| 6 | マイページ注文詳細ページ | 中 | 未実装（下記メモ参照） |
| 7 | 一時ファイル自動クリーンアップ | 低 | 未実装（下記メモ参照） |
| 8 | 注文ステータス変更通知メール | 低 | 未実装 |
| 9 | 本番環境デプロイ設定 | — | 未実装 |

---

### ① AI API連携プレビュー生成

現在はPHP GDライブラリでグレースケール＋反転の近似処理のみ。
将来的にAI APIと連携し、より精度の高いエングレービング風画像を生成する。

---

### ② 決済連携（Stripe等）

`/payment/{token}` ページは実装済み。POSTで `payment_status=Paid` に更新するが、実際の課金処理は未実装。
Stripe Checkout または Payment Intents API との連携が必要。

---

### ③ 入力バリデーション強化

現状の未対応バリデーション：

| 項目 | 現状 | 対応内容 |
|------|------|----------|
| 郵便番号フォーマット | 文字列のみ | `regex:/^\d{3}-?\d{4}$/` |
| 電話番号フォーマット | 文字列のみ | `regex:/^[0-9\-+]{10,15}$/` |
| 画像ファイルサイズ上限 | 10MB | ユーザーへのエラーメッセージ改善 |
| 注文数量上限 | min:1 max:10 | 在庫数との連動（未実装） |
| XSS対策 | Bladeの`{{ }}`で自動エスケープ | カスタム入力欄の確認 |

---

### ④ セキュリティー対策

| 項目 | 現状 | 対応内容 |
|------|------|----------|
| CSRF | LaravelデフォルトCSRFトークン | 確認済み |
| SQLインジェクション | Eloquent ORM使用 | 確認済み |
| 認可チェック | 主要ルートに実装済み | Policy クラスへの統一化を検討 |
| レートリミット | 未設定 | 注文・メール送信APIにリミット追加 |
| ファイルアップロード | MIME・サイズチェックあり | 実行可能ファイルの除外確認 |
| 決済トークン | 64文字ランダム | 使用済みトークンの再利用防止確認 |
| 管理画面アクセス制限 | Filamentの認証のみ | IP制限・2FAを本番前に検討 |

---

### ⑤ 全体テスト

本番デプロイ前に確認すべきテスト項目：

**購入フロー**
- [ ] 相談なし注文の完全フロー（入力→プレビュー→確定→マイページ反映）
- [ ] 相談あり注文の完全フロー（入力→相談→プレビュー受信→決済→完了）
- [ ] カメラ撮影フロー（鼻紋・シルエット両方）
- [ ] マイページからのキャンセル
- [ ] セッション切れ時のリダイレクト

**管理画面**
- [ ] 全ステータス遷移ボタンの動作確認
- [ ] プレビュー画像送信→メール受信確認
- [ ] 決済メール送信→決済ページ表示→支払い完了

**メール**
- [ ] 全5種類のメール送信確認（MailHog）
- [ ] メール送信失敗時にログ記録されること

**エッジケース**
- [ ] 決済トークン期限切れ（7日後）
- [ ] 支払済み注文への再アクセス
- [ ] 他ユーザーの注文へのアクセス（403確認）

---

### ⑥ マイページ注文詳細ページ

**実装内容**
- ルート：`GET /mypage/orders/{order}` → `mypage.order.show`
- `ProfileController::orderShow()` 追加（自分の注文か403チェック）
- ビュー：ステータス・注文内容・プレビュー履歴・配送先・決済状況を表示
- マイページ注文カードに「詳細を見る」リンク追加

**相談履歴（DogGoodsConsultation）の保持方針**
- テキスト（`sent_at`・`reply_message`）は残す → トラブル対応の記録として有効
- 注文完了後に `preview_image` のファイルだけ削除、レコード自体は残す
- 退会処理時にユーザー紐づきの画像ファイルを一括削除する処理も将来的に必要

優先度：中

---

### ⑦ 一時ファイル自動クリーンアップ

`storage/app/public/orders/temp/` 以下に注文途中離脱で残るファイルを定期削除する。

**推奨実装方法：Artisanコマンド + スケジューラー**
```php
// app/Console/Commands/CleanTempImages.php
// 更新日時が24時間以上前のファイルを削除

// app/Console/Kernel.php
$schedule->command('app:clean-temp-images')->daily();
```

優先度：低（本番デプロイ前までに対応推奨）
