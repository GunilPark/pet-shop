@extends('emails.layouts.base')
@section('title', 'ご注文受付完了 #' . $order->id)
@section('body')
<span class="badge">ORDER RECEIVED</span>
<div class="greeting">
  {{ $order->user->name }} 様<br><br>
  この度はINU GOODSにご注文いただき、誠にありがとうございます。<br>
  ご注文を受け付けました。担当者が内容を確認し次第、ご連絡いたします。
</div>

@include('emails.partials.order-info')

<div class="notice">
  📌 <strong>ご確認ください</strong><br>
  ・担当者による画像確認後、加工作業を開始いたします。<br>
  ・仕上がりプレビューをメールにてお送りいたします。<br>
  ・ご不明な点はお気軽にお問い合わせください。
</div>

<div style="text-align:center; margin-top:32px;">
  <a href="{{ config('app.url') }}/mypage" class="btn">
    マイページで注文を確認する
  </a>
</div>
@endsection
