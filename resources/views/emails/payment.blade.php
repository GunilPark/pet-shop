@extends('emails.layouts.base')
@section('title', 'お支払いのご案内 #' . $order->id)
@section('body')
<span class="badge">PAYMENT REQUEST</span>
<div class="greeting">
  {{ $order->user->name }} 様<br><br>
  プレビューのご確認ありがとうございます。<br>
  以下の内容にてお支払いをお願いいたします。
</div>

<div class="section">
  <div class="section-title">Order ／ ご注文内容</div>
  <div class="info-row"><span class="info-label">注文番号</span><span class="info-value">#{{ $order->id }}</span></div>
  <div class="info-row"><span class="info-label">商品名</span><span class="info-value">{{ $item->name }}</span></div>
  <div class="info-row"><span class="info-label">お支払い金額</span><span class="info-value" style="color:#c9a96e; font-size:18px; font-weight:bold;">¥{{ number_format($item->price) }}（税込）</span></div>
</div>

@if($order->processed_image)
<div class="section">
  <div class="section-title">Preview ／ 仕上がりプレビュー</div>
  <div class="img-box">
    <img src="{{ asset('storage/' . $order->processed_image) }}" alt="仕上がりプレビュー">
  </div>
</div>
@endif

<div style="text-align:center; margin:32px 0 16px;">
  <a href="{{ config('app.url') }}/payment/{{ $order->payment_token }}" class="btn">
    お支払いはこちら →
  </a>
</div>

<div class="notice">
  ⏰ <strong>有効期限：{{ now()->addDays(7)->format('Y年m月d日') }} まで</strong><br><br>
  ・上記リンクは7日間有効です。期限を過ぎた場合はご連絡ください。<br>
  ・このURLは第三者に共有しないようお願いいたします。<br>
  ・お支払い完了後、製作・発送を開始いたします。
</div>
@endsection
