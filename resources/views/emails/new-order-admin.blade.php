@extends('emails.layouts.base')
@section('title', '新規注文通知 #' . $order->id)
@section('body')
<span class="badge">NEW ORDER</span>
<div class="greeting">
  新規注文が入りました。<br>
  管理画面よりご確認の上、対応をお願いいたします。
</div>

@include('emails.partials.order-info')

@if($order->uploaded_image)
<div class="section">
  <div class="section-title">Image ／ アップロード画像</div>
  <div class="img-box">
    <img src="{{ asset('storage/' . $order->uploaded_image) }}" alt="アップロード画像">
  </div>
</div>
@endif

@if($order->admin_memo)
<div class="section">
  <div class="section-title">Memo ／ 管理メモ</div>
  <div class="highlight-box">{{ $order->admin_memo }}</div>
</div>
@endif

<div style="text-align:center; margin-top:32px;">
  <a href="{{ config('app.url') }}/admin/dog-goods-orders/{{ $order->id }}/edit" class="btn btn-dark">
    管理画面で確認する →
  </a>
</div>
@endsection
