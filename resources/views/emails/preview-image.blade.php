@extends('emails.layouts.base')
@section('title', '加工プレビューのご確認 #' . $order->id)
@section('body')
<span class="badge">PREVIEW READY</span>
<div class="greeting">
  {{ $order->user->name }} 様<br><br>
  お待たせいたしました。ご注文の加工プレビューが完成しました。<br>
  内容をご確認の上、ご返答をお願いいたします。
</div>

<div class="section">
  <div class="section-title">Order ／ ご注文番号</div>
  <div class="info-row"><span class="info-label">注文番号</span><span class="info-value">#{{ $order->id }}</span></div>
  <div class="info-row"><span class="info-label">商品名</span><span class="info-value">{{ $item->name }}</span></div>
</div>

@if($consultation->preview_image)
<div class="section">
  <div class="section-title">Preview ／ 加工プレビュー画像</div>
  <div class="img-box">
    <img src="{{ asset('storage/' . $consultation->preview_image) }}" alt="加工プレビュー画像">
  </div>
</div>
@endif

@if($consultation->reply_message)
<div class="section">
  <div class="section-title">Comment ／ 担当者コメント</div>
  <div class="highlight-box">{{ $consultation->reply_message }}</div>
</div>
@endif

<div class="notice">
  📌 <strong>次のステップ</strong><br>
  ・プレビューをご確認いただき、問題がなければご返答ください。<br>
  ・修正が必要な場合はお気軽にお知らせください。<br>
  ・ご確認いただきましたら、決済のご案内をお送りいたします。
</div>

<div style="text-align:center; margin-top:32px;">
  <a href="{{ config('app.url') }}/mypage" class="btn">
    マイページで確認する
  </a>
</div>
@endsection
