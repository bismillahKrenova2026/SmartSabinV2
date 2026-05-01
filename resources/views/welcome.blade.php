@extends('layouts.app')

@section('title', $site['name'] . ' • Beranda')

@section('content')
    <section class="grid gap-8 lg:grid-cols-[1.3fr_0.7fr] lg:items-center">
        <div class="space-y-6">
            <div class="inline-flex items-center gap-2 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-4 py-2 text-sm text-emerald-200">
                <span>●</span> {{ $site['tagline'] }}
            </div>

            <div class="space-y-4">
                <h1 class="max-w-3xl text-4xl font-bold tracking-tight text-white sm:text-5xl">
                    Memonitor lahan, air, dan tanaman dalam satu alur yang lebih rapi.
                </h1>
                <p class="max-w-2xl text-lg leading-8 text-slate-300">
                    Website ini menjadi pintu masuk publik untuk mengenal proyek, membaca literasi tanaman, lalu masuk ke dashboard setelah login untuk memantau sensor, rekomendasi tanaman, dan kondisi air secara terpadu.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('login') }}" class="rounded-2xl bg-emerald-500 px-5 py-3 font-semibold text-slate-950 transition hover:bg-emerald-400">
                    Masuk ke Dashboard
                </a>
                <a href="#literasi" class="rounded-2xl border border-white/10 bg-white/5 px-5 py-3 font-semibold text-slate-100 transition hover:bg-white/10">
                    Lihat Literasi Tanaman
                </a>
            </div>
        </div>

        <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6 shadow-2xl shadow-emerald-950/30 backdrop-blur-xl">
            <div class="grid gap-4">
                <div class="rounded-3xl bg-slate-900/80 p-5 ring-1 ring-white/5">
                    <p class="text-sm text-slate-400">Fokus Sistem</p>
                    <p class="mt-2 text-xl font-semibold text-white">Rekomendasi tanaman + monitoring air</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-3xl bg-slate-900/80 p-5 ring-1 ring-white/5">
                        <p class="text-sm text-slate-400">Sumber data</p>
                        <p class="mt-2 font-semibold text-emerald-300">Blynk API</p>
                    </div>
                    <div class="rounded-3xl bg-slate-900/80 p-5 ring-1 ring-white/5">
                        <p class="text-sm text-slate-400">Rekomendasi</p>
                        <p class="mt-2 font-semibold text-sky-300">Spreadsheet API</p>
                    </div>
                </div>
                <div class="rounded-3xl bg-gradient-to-br from-emerald-500/15 to-sky-500/10 p-5 ring-1 ring-white/5">
                    <p class="text-sm text-slate-300">Alur kerja</p>
                    <p class="mt-2 text-sm leading-7 text-slate-200">Login → baca kondisi lahan → pilih tanaman yang sesuai → sistem memantau tanaman aktif → air aman diteruskan, air tidak aman diproses lebih dulu.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-16 grid gap-6 md:grid-cols-3">
        <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
            <p class="text-sm text-slate-400">01</p>
            <h2 class="mt-2 text-xl font-semibold text-white">Pengenalan proyek</h2>
            <p class="mt-3 text-slate-300">Halaman publik menampilkan konsep sistem, tujuan, dan alur kerja tanpa mengharuskan login terlebih dahulu.</p>
        </div>
        <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
            <p class="text-sm text-slate-400">02</p>
            <h2 class="mt-2 text-xl font-semibold text-white">Literasi tanaman</h2>
            <p class="mt-3 text-slate-300">Pengunjung dapat membaca tanaman yang cocok, keunggulannya, serta kondisi lingkungan yang ideal.</p>
        </div>
        <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
            <p class="text-sm text-slate-400">03</p>
            <h2 class="mt-2 text-xl font-semibold text-white">Dashboard terproteksi</h2>
            <p class="mt-3 text-slate-300">Setelah login, user masuk ke dashboard untuk melihat saran tanaman, memantau air, dan memilih tanaman aktif.</p>
        </div>
    </section>

    <section id="literasi" class="mt-16 space-y-6">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-300">Literasi tanaman</p>
            <h2 class="mt-2 text-3xl font-bold text-white">Tanaman yang bisa dipilih berdasarkan karakter lahan</h2>
            <p class="mt-3 text-slate-300">Setiap tanaman dipadankan dengan kondisi pH, lingkungan ideal, dan kebutuhan air agar user bisa memilih dengan lebih percaya diri.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($plants as $plant)
                <article class="rounded-[2rem] border border-white/10 bg-slate-900/70 p-6 ring-1 ring-white/5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-semibold text-emerald-300">{{ $plant['badge'] }}</span>
                            <h3 class="mt-4 text-2xl font-bold text-white">{{ $plant['name'] }}</h3>
                        </div>
                        <span class="rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-xs text-slate-300">pH {{ $plant['ideal_ph_min'] }}–{{ $plant['ideal_ph_max'] }}</span>
                    </div>

                    <p class="mt-4 leading-7 text-slate-300">{{ $plant['description'] }}</p>

                    <div class="mt-5 space-y-3">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Keunggulan</p>
                            <ul class="mt-2 space-y-2 text-sm text-slate-300">
                                @foreach ($plant['advantages'] as $advantage)
                                    <li class="flex gap-2"><span class="text-emerald-400">•</span><span>{{ $advantage }}</span></li>
                                @endforeach
                            </ul>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Lingkungan ideal</p>
                            <p class="mt-2 text-sm leading-7 text-slate-300">{{ $plant['ideal_environment'] }}</p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
