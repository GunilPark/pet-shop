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
| order_status | enum | pending / paid / preparing / shipping / delivered / canceled |
| processing_status | enum | pending / reviewing / processing / completed / rejected |
| consultation_status | enum | none / waiting / replied / resolved |
| payment_status | enum | unsent / sent / paid / expired |
| uploaded_image | string | nullable（アップロード画像path） |
| processed_image | string | nullable（加工済画像path） |
| custom_options | json | nullable（注文フォーム入力内容＋配送先住所） |
| is_consultation | boolean | default: false（メール相談フラグ） |
| admin_memo | text | nullable |
| payment_token | string(64) | nullable / unique（決済URL用） |
| preview_sent_at | timestamp | nullable |
| payment_sent_at | timestamp | nullable |
| ordered_at | timestamp | |
| deleted_at | timestamp | SoftDelete |

#### custom_options の構造（JSON）
```json
{
  "material": "black|wood",
  "engraving_type": "nose_print|silhouette",
  "name": "ペット名",
  "breed": "犬種",
  "birthday": "YYYY.MM.DD",
  "message": "裏面メッセージ",
  "shipping_name": "宛名",
  "postal_code": "1234567",
  "prefecture": "都道府県",
  "city": "市区町村",
  "address_line": "番地・建物名",
  "phone": "電話番号",
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
| Case | Value | ラベル | 運用状況 |
|------|-------|--------|----------|
| Basic | basic | 基本商品 | 有効 |
| NameTag | name_tag | ネームタグ | 有効（メイン商品） |
| NosePrintTag | nose_print_tag | 鼻紋ネームタグ | 旧型（DB互換のため残存） |
| SilhouetteTag | silhouette_tag | シルエットネームタグ | 旧型 |
| SilhouetteKeychain | silhouette_keychain | シルエットキーホルダー | 旧型 |

### OrderStatus
`pending` → `paid` → `preparing` → `shipping` → `delivered`（または `canceled`）

### ProcessingStatus
`pending` → `reviewing` → `processing` → `completed`（または `rejected`）

### ConsultationStatus
`none` → `waiting` → `replied` → `resolved`

### PaymentStatus
`unsent` → `sent` → `paid`（または `expired`）

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
| `/mypage` | mypage.blade.php | マイページ（住所管理・注文・申請履歴） |
| `/mypage/address` | — | 住所更新（PATCH） |
| `/dog-profile/create` | dog-profile/create.blade.php | 犬プロフィール登録 |
| `/dog-profile/{id}/edit` | dog-profile/edit.blade.php | 犬プロフィール編集 |
| `/payment/{token}` | payment/show.blade.php | 決済ページ（ログイン不要） |

### 管理画面（Filament）
| URL | 説明 |
|-----|------|
| `/admin` | ダッシュボード |
| `/admin/users` | ユーザー管理（犬プロフィールRelationManager内包） |
| `/admin/dog-goods-items` | グッズ商品管理（複数画像・ガイド文管理） |
| `/admin/dog-goods-orders` | 注文管理（加工開始／プレビュー送信／決済メール送信） |
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
      ├─ DogGoodsOrder 作成
      ├─ 管理者へ新規注文通知メール（NewOrderAdminMail）
      └─ ユーザーへ受付完了メール（NewOrderUserMail）
```

### 6-2. メール相談フロー（is_consultation=true）
```
顧客が「メールで相談する」を選択
 │
 ├─ 注文登録（is_consultation=true）
 ├─ 管理者へ相談申請メール（ConsultationMail）
 │
 └─ 管理画面（Filament）
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
| アクション | 条件 | 内容 |
|-----------|------|------|
| 加工開始 | processing_status = reviewing | reviewing → processing に変更 |
| プレビュー送信 | is_consultation = true | 画像＋コメント入力 → PreviewImageMail 送信 |
| 決済メール送信 | is_consultation = true かつ未支払 | PaymentMail 送信・トークン生成 |

### DogGoodsEventResource 主要機能
- 定員・申請数のリアルタイム表示
- DogEventApplyResource でステータス管理（approve / reject アクション）

---

## 10. 認証・権限

| 種別 | 実装 |
|------|------|
| 顧客認証 | Laravel Breeze（セッション） |
| 管理者認証 | Filament 独自（`php artisan make:filament-user`） |
| 犬プロフィール保護 | `abort_if($dogProfile->user_id !== auth()->id(), 403)` |
| 注文フォーム保護 | `auth` ミドルウェア |
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

## 12. 今後の拡張予定

| 機能 | 状態 |
|------|------|
| 決済連携（Stripe等） | 未実装（/payment/{token} ページのみ実装済み） |
| AI API連携プレビュー生成 | 未実装（現在はPHP GDで近似処理） |
| カレンダー・時計商品タイプ追加 | 未実装 |
| 注文ステータス変更通知メール | 未実装 |
| 多言語対応 | 未実装 |
| 本番環境デプロイ設定 | 未実装 |
