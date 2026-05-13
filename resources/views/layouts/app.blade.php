<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GUNIL PET SHOP')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Noto Sans JP', sans-serif; }</style>
</head>
<body class="bg-slate-50 flex flex-col min-h-screen">

    <nav class="bg-white shadow-sm sticky top-0 z-50 p-4 border-b border-orange-100">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-orange-500">🐾 GUNIL PET SHOP</a>
            <div class="hidden md:flex gap-10 font-bold">
                <a href="/" class="hover:text-orange-500">TOPページ</a>
                <a href="/event" class="hover:text-orange-500">犬RUNイベント</a>
                <a href="/goods" class="hover:text-orange-500">犬グッズ</a>
            </div>
            <a href="/admin" class="bg-orange-500 text-white px-4 py-2 rounded-full text-sm font-bold">管理者ログイン</a>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-white py-10">
        <div class="container mx-auto text-center px-6">
            <p class="text-orange-500 font-bold mb-4 italic text-lg text-orange-500">GUNIL PET SHOP</p>
            <p class="text-slate-400 text-sm italic text-slate-400">© 2026 Gunil Pet Shop. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>