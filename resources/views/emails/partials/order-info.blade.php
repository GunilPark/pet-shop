{{-- 共通：注文情報セクション --}}
@php
    $opts = $order->custom_options ?? [];
    $materialLabel = match($opts['material'] ?? '') {
        'black' => 'ブラック（軍番タグ）', 'wood' => 'ウッド（木製キーホルダー）', default => '—',
    };
    $engravingLabel = match($opts['engraving_type'] ?? '') {
        'nose_print' => '鼻紋刻印', 'silhouette' => 'シルエット刻印', default => '—',
    };
@endphp
<div class="section">
  <div class="section-title">Order Info ／ ご注文情報</div>
  <div class="info-row"><span class="info-label">注文番号</span><span class="info-value">#{{ $order->id }}</span></div>
  <div class="info-row"><span class="info-label">申請日時</span><span class="info-value">{{ $order->ordered_at->format('Y年m月d日 H:i') }}</span></div>
  <div class="info-row"><span class="info-label">商品名</span><span class="info-value">{{ $item->name }}</span></div>
  <div class="info-row"><span class="info-label">素材</span><span class="info-value">{{ $materialLabel }}</span></div>
  <div class="info-row"><span class="info-label">刻印タイプ</span><span class="info-value">{{ $engravingLabel }}</span></div>
</div>

<div class="section">
  <div class="section-title">Dog Info ／ ワンちゃん情報</div>
  <div class="info-row"><span class="info-label">お名前</span><span class="info-value">{{ $opts['name'] ?? '—' }}</span></div>
  <div class="info-row"><span class="info-label">犬種</span><span class="info-value">{{ $opts['breed'] ?? '—' }}</span></div>
  <div class="info-row"><span class="info-label">誕生日</span><span class="info-value">{{ $opts['birthday'] ?? '—' }}</span></div>
  <div class="info-row"><span class="info-label">メッセージ刻印</span><span class="info-value">{{ ($opts['message'] ?? '') ?: 'なし' }}</span></div>
</div>

<div class="section">
  <div class="section-title">Shipping ／ 配送先情報</div>
  @php
      $postal = preg_replace('/^(\d{3})(\d{4})$/', '$1-$2', preg_replace('/[^0-9]/', '', $opts['postal_code'] ?? ''));
  @endphp
  <div class="info-row"><span class="info-label">お名前（宛名）</span><span class="info-value">{{ $opts['shipping_name'] ?? '—' }}</span></div>
  <div class="info-row"><span class="info-label">電話番号</span><span class="info-value">{{ $opts['phone'] ?? '—' }}</span></div>
  <div class="info-row"><span class="info-label">郵便番号</span><span class="info-value">〒{{ $postal }}</span></div>
  <div class="info-row"><span class="info-label">住所</span><span class="info-value">{{ ($opts['prefecture'] ?? '') . ($opts['city'] ?? '') }}<br>{{ $opts['address_line'] ?? '' }}</span></div>
</div>
