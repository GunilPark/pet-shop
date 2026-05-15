@php
    $brandColor = '#c9a96e';   // ゴールド
    $darkColor  = '#1a1a1a';
    $lightBg    = '#faf9f7';
    $borderColor= '#e8e0d5';
@endphp
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'INU GOODS')</title>
<style>
  body { margin:0; padding:0; background:#f5f2ee; font-family: 'Helvetica Neue', Arial, 'Hiragino Sans', sans-serif; color:#333; }
  .wrapper { max-width:600px; margin:32px auto; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08); }
  .header { background:{{ $darkColor }}; padding:32px 40px; text-align:center; }
  .header .logo { color:{{ $brandColor }}; font-size:22px; font-weight:bold; letter-spacing:4px; }
  .header .tagline { color:rgba(255,255,255,0.5); font-size:11px; letter-spacing:3px; margin-top:6px; }
  .badge { display:inline-block; background:{{ $brandColor }}; color:#fff; font-size:11px; font-weight:bold; padding:4px 14px; border-radius:20px; letter-spacing:2px; margin-bottom:20px; }
  .body { padding:40px; }
  .greeting { font-size:15px; color:#555; margin-bottom:24px; line-height:1.8; }
  .section { margin-bottom:28px; }
  .section-title { font-size:11px; font-weight:bold; color:{{ $brandColor }}; letter-spacing:3px; text-transform:uppercase; border-bottom:1px solid {{ $borderColor }}; padding-bottom:8px; margin-bottom:16px; }
  .info-row { display:flex; padding:8px 0; border-bottom:1px solid #f0ede8; font-size:14px; }
  .info-label { color:#999; width:140px; flex-shrink:0; }
  .info-value { color:#333; font-weight:500; flex:1; }
  .highlight-box { background:{{ $lightBg }}; border-left:3px solid {{ $brandColor }}; padding:16px 20px; border-radius:0 8px 8px 0; margin:16px 0; font-size:14px; line-height:1.8; }
  .img-box { text-align:center; margin:20px 0; }
  .img-box img { max-width:100%; border-radius:8px; border:1px solid {{ $borderColor }}; }
  .btn { display:inline-block; background:{{ $brandColor }}; color:#fff !important; text-decoration:none; padding:14px 40px; border-radius:8px; font-weight:bold; font-size:15px; letter-spacing:1px; margin:8px 0; }
  .btn-dark { background:{{ $darkColor }}; }
  .notice { background:#fffbf0; border:1px solid #f0e0a0; border-radius:8px; padding:16px 20px; font-size:13px; color:#888; line-height:1.8; margin:20px 0; }
  .footer { background:#f5f2ee; padding:28px 40px; text-align:center; border-top:1px solid {{ $borderColor }}; }
  .footer p { color:#aaa; font-size:11px; line-height:1.8; margin:2px 0; }
  .footer .brand { color:{{ $brandColor }}; font-weight:bold; font-size:13px; letter-spacing:3px; margin-bottom:8px; }
  @media (max-width:600px) {
    .body { padding:24px 20px; }
    .info-row { flex-direction:column; }
    .info-label { width:auto; margin-bottom:2px; }
  }
</style>
</head>
<body>
<div class="wrapper">
  {{-- ヘッダー --}}
  <div class="header">
    <div class="logo">🐾 INU GOODS</div>
    <div class="tagline">HANDCRAFTED FOR YOUR DOG</div>
  </div>

  {{-- 本文 --}}
  <div class="body">
    @yield('body')
  </div>

  {{-- フッター --}}
  <div class="footer">
    <p class="brand">INU GOODS</p>
    <p>このメールはシステムより自動送信されています。</p>
    <p>ご不明な点はお問い合わせください。</p>
  </div>
</div>
</body>
</html>
