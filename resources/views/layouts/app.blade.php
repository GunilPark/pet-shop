<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'INU GOODS'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CDN (開発用) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-white text-slate-900">

        {{-- ナビゲーション --}}
        <nav class="bg-white border-b border-slate-100 sticky top-0 z-50">
            <div class="container mx-auto px-6 h-16 flex items-center justify-between">
                <a href="/" class="text-xl font-black text-slate-900 hover:text-orange-500 transition">
                    🐶 INU GOODS
                </a>
                <div class="flex items-center gap-6 text-sm font-bold">
                    <a href="/goods" class="text-slate-600 hover:text-orange-500 transition">グッズ</a>
                    <a href="/event" class="text-slate-600 hover:text-orange-500 transition">イベント</a>
                    @auth
                        <a href="{{ route('mypage') }}" class="text-slate-600 hover:text-orange-500 transition">マイページ</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-slate-400 hover:text-red-500 transition">ログアウト</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 hover:text-orange-500 transition">ログイン</a>
                        <a href="{{ route('register') }}" class="bg-orange-500 text-white px-4 py-2 rounded-xl hover:bg-orange-600 transition">会員登録</a>
                    @endauth
                </div>
            </div>
        </nav>

        {{-- メインコンテンツ --}}
        <main>
            @yield('content')
        </main>

        {{-- フッター --}}
        <footer class="bg-slate-900 text-slate-400 py-10 mt-16">
            <div class="container mx-auto px-6 text-center text-sm">
                <p class="font-black text-white text-lg mb-2">🐶 INU GOODS</p>
                <p>© {{ date('Y') }} Gunil Pet Shop. All rights reserved.</p>
            </div>
        </footer>

    </body>
</html>
