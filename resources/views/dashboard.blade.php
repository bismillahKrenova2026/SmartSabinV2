@extends('layouts.app')

@section('title', $site['name'] . ' • Dashboard')

@section('content')
    <section class="space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-300">Dashboard user</p>
                <h1 class="mt-2 text-3xl font-bold text-white">Kondisi lahan dan air yang siap dipantau</h1>
                <p class="mt-3 max-w-3xl text-slate-300">Halaman ini menampilkan data sensor terbaru, status air, dan rekomendasi tanaman berdasarkan kondisi aktual yang terbaca dari Blynk serta Spreadsheet.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <form method="POST" action="{{ route('sensor.sync') }}">
                    @csrf
                    <button class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-medium text-slate-100 transition hover:bg-white/10">
                        Tarik data terbaru
                    </button>
                </form>
                <a href="{{ route('recommendation') }}" class="rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400">Pilih tanaman</a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6 ring-1 ring-white/5">
                <p class="text-sm text-slate-400">pH Air</p>
                <p class="mt-3 text-4xl font-bold text-white">{{ data_get($sensor, 'ph_air') !== null ? number_format((float) data_get($sensor, 'ph_air'), 1) : '-' }}</p>
            </div>
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6 ring-1 ring-white/5">
                <p class="text-sm text-slate-400">pH Tanah</p>
                <p class="mt-3 text-4xl font-bold text-white">{{ data_get($sensor, 'ph_tanah') !== null ? number_format((float) data_get($sensor, 'ph_tanah'), 1) : '-' }}</p>
            </div>
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6 ring-1 ring-white/5">
                <p class="text-sm text-slate-400">Kelembaban Tanah</p>
                <p class="mt-3 text-4xl font-bold text-white">{{ data_get($sensor, 'kelembaban_tanah') !== null ? number_format((float) data_get($sensor, 'kelembaban_tanah'), 0) : '-' }}%</p>
            </div>
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6 ring-1 ring-white/5">
                <p class="text-sm text-slate-400">Suhu Udara</p>
                <p class="mt-3 text-4xl font-bold text-white">{{ data_get($sensor, 'suhu_udara') !== null ? number_format((float) data_get($sensor, 'suhu_udara'), 1) : '-' }}°C</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
            <article class="rounded-[2rem] border border-white/10 bg-slate-900/80 p-6 ring-1 ring-white/5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-400">Rekomendasi otomatis</p>
                        <h2 class="mt-1 text-2xl font-bold text-white">{{ $bestRecommendation['name'] ?? 'Belum ada rekomendasi' }}</h2>
                    </div>
                    <span class="rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-semibold text-emerald-300">Skor {{ $bestRecommendation['score'] ?? 0 }}</span>
                </div>

                <p class="mt-4 text-slate-300">{{ $bestRecommendation['description'] ?? 'Sistem belum membaca data yang cukup untuk memberikan rekomendasi.' }}</p>

                @if (! empty($bestRecommendation['reasons'] ?? []))
                    <div class="mt-5 rounded-3xl border border-white/10 bg-white/5 p-5">
                        <p class="text-sm font-semibold text-white">Alasan utama</p>
                        <ul class="mt-3 space-y-2 text-sm text-slate-300">
                            @foreach ($bestRecommendation['reasons'] as $reason)
                                <li class="flex gap-2"><span class="text-emerald-400">•</span><span>{{ $reason }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Spreadsheet</p>
                        <p class="mt-2 text-sm text-slate-300">{{ data_get($spreadsheetRecommendation, 'rekomendasi_tanaman', '-') }}</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Tanaman aktif</p>
                        <p class="mt-2 text-sm text-slate-300">{{ $activePlant['name'] ?? 'Belum dipilih' }}</p>
                    </div>
                </div>
            </article>

            <aside class="space-y-6">
                <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6 ring-1 ring-white/5">
                    <p class="text-sm text-slate-400">Status air</p>
                    <div class="mt-3 flex items-center gap-3">
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $waterStatus['badge'] }}">{{ $waterStatus['label'] }}</span>
                        <span class="text-sm text-slate-300">{{ $waterStatus['action'] }}</span>
                    </div>
                    <p class="mt-4 text-sm leading-7 text-slate-300">{{ $waterStatus['description'] }}</p>
                </div>

                <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6 ring-1 ring-white/5">
                    <p class="text-sm text-slate-400">Tanaman aktif</p>
                    <h3 class="mt-2 text-2xl font-semibold text-white">{{ $activePlant['name'] ?? 'Belum ada tanaman aktif' }}</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-300">Setelah user memilih tanaman, monitoring akan fokus pada tanaman tersebut. Jika pilihan salah, user bisa mengganti tanaman kapan saja.</p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ route('monitoring') }}" class="rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400">Buka monitoring</a>
                        <a href="{{ route('recommendation') }}" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-medium text-slate-100 transition hover:bg-white/10">Ubah pilihan</a>
                    </div>
                </div>
            </aside>
        </div>

        <div class="rounded-[2rem] border border-white/10 bg-slate-900/80 p-6 ring-1 ring-white/5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm text-slate-400">Rekomendasi alternatif</p>
                    <h2 class="text-2xl font-bold text-white">Top kandidat tanaman</h2>
                </div>
                <span class="text-sm text-slate-500">{{ $analysis['water_state'] === 'aman' ? 'Air aman' : 'Air perlu diproses' }}</span>
            </div>

            <div class="mt-6 grid gap-4 lg:grid-cols-4">
                @forelse ($recommendedPlants as $plant)
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="text-lg font-semibold text-white">{{ $plant['name'] }}</h3>
                            <span class="rounded-full bg-sky-500/15 px-3 py-1 text-xs font-semibold text-sky-300">{{ $plant['score'] }}</span>
                        </div>
                        <p class="mt-3 text-sm leading-7 text-slate-300">{{ $plant['ideal_environment'] }}</p>
                    </div>
                @empty
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5 text-sm text-slate-400">Belum ada kandidat tanaman.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
