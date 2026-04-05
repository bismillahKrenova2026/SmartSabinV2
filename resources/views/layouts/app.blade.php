<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('smartfarming.site.name', config('app.name')))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { color-scheme: dark; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="fixed inset-0 -z-10 bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_45%),radial-gradient(circle_at_bottom,_rgba(59,130,246,0.12),_transparent_40%)]"></div>

    <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/80 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-10 rounded-2xl object-contain">
                <div>
                    <p class="text-sm font-semibold tracking-[0.25em] text-emerald-300 uppercase">Smart Sabin</p>
                    <p class="text-xs text-slate-400">Smart farming monitoring</p>
                </div>
            </a>

            <nav class="hidden items-center gap-6 text-sm text-slate-300 md:flex">
                <a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="transition hover:text-white">Dashboard</a>
                    <a href="{{ route('recommendation') }}" class="transition hover:text-white">Rekomendasi</a>
                    <a href="{{ route('monitoring') }}" class="transition hover:text-white">Monitoring</a>
                @else
                    <a href="{{ route('login') }}" class="transition hover:text-white">Masuk</a>
                @endauth
            </nav>

            <div class="flex items-center gap-3">
                @auth
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-slate-200 transition hover:bg-white/10">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </header>

    @if (session('status'))
        <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <footer class="border-t border-white/10 py-6 text-center text-sm text-slate-500">
        © {{ date('Y') }} Smart Sabin • Sistem monitoring pertanian berbasis IoT
    </footer>

    @stack('scripts')
</body>
</html>
