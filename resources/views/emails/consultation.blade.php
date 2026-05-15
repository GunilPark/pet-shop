@php
    $opts            = $order->custom_options ?? [];
    $materialLabel   = match($opts['material'] ?? '') {
        'black'  => 'ブラック',
        'wood'   => 'ウッド（木製）',
        default  => $opts['material'] ?? '—',
    };
    $engravingLabel  = match($opts['engraving_type'] ?? '') {
        'nose_print' => '鼻紋刻印',
        'silhouette' => 'シルエット刻印',
        default      => $opts['engraving_type'] ?? '—',
    };
    $postalFormatted = preg_replace('/^(\d{3})(\d{4})$/', '$1-$2', preg_replace('/[^0-9]/', '', $opts['postal_code'] ?? ''));
    $messageText     = ($opts['message'] ?? '') !== '' ? $opts['message'] : 'なし';
    $tempImage       = $opts['temp_image'] ?? '—';
@endphp
INU GOODS 管理者様

{{ $item->name }}に関するご相談申請がありました。

━━━━━━━━━━━━━━
■ ご注文情報
━━━━━━━━━━━━━━

注文番号：#{{ $order->id }}
申請日時：{{ $order->ordered_at->format('Y-m-d H:i') }}

商品名：
{{ $item->name }}

━━━━━━━━━━━━━━
■ お客様情報
━━━━━━━━━━━━━━

お名前：
{{ $opts['shipping_name'] ?? '—' }}

電話番号：
{{ $opts['phone'] ?? '—' }}

配送先住所：
〒{{ $postalFormatted }}
{{ ($opts['prefecture'] ?? '') . ($opts['city'] ?? '') }} {{ $opts['address_line'] ?? '' }}

━━━━━━━━━━━━━━
■ ワンちゃん情報
━━━━━━━━━━━━━━

お名前：
{{ $opts['name'] ?? '—' }}

犬種：
{{ $opts['breed'] ?? '—' }}

誕生日：
{{ $opts['birthday'] ?? '—' }}

刻印タイプ：
{{ $engravingLabel }}

素材カラー：
{{ $materialLabel }}

メッセージ刻印：
{{ $messageText }}

━━━━━━━━━━━━━━
■ 添付画像
━━━━━━━━━━━━━━

アップロード画像：
{{ $tempImage }}

━━━━━━━━━━━━━━
管理画面より内容をご確認の上、
必要に応じて画像加工・ご連絡対応をお願いいたします。

INU GOODS System
