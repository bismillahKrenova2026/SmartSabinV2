@extends('layouts.app')

@section('title', $site['name'] . ' • Monitoring')

@section('content')
    <section class="space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-300">Monitoring tanaman aktif</p>
                <h1 class="mt-2 text-3xl font-bold text-white">Pantau tanaman pilihan dan kondisi air secara bersamaan</h1>
                <p class="mt-3 max-w-3xl text-slate-300">Halaman ini menunjukkan tanaman yang sudah dipilih user. Jika air aman, sistem meneruskan air ke lahan. Jika belum aman, air diproses terlebih dahulu.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('recommendation') }}" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-medium text-slate-100 transition hover:bg-white/10">Ubah tanaman</a>
                <form method="POST" action="{{ route('sensor.sync') }}">
                    @csrf
                    <button class="rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400">Tarik data terbaru</button>
                </form>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
                <p class="text-sm text-slate-400">Tanaman aktif</p>
                <h2 class="mt-3 text-2xl font-bold text-white">{{ $activePlant['name'] ?? 'Belum ada tanaman aktif' }}</h2>
                <p class="mt-2 text-sm text-slate-300">{{ $activePlant['description'] ?? 'Pilih tanaman dari halaman rekomendasi untuk mengunci monitoring pada satu tanaman.' }}</p>
            </div>
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
                <p class="text-sm text-slate-400">Status air</p>
                <span class="mt-3 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $waterStatus['badge'] }}">{{ $waterStatus['label'] }}</span>
                <p class="mt-3 text-sm leading-7 text-slate-300">{{ $waterStatus['description'] }}</p>
            </div>
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
                <p class="text-sm text-slate-400">Aksi sistem</p>
                <p class="mt-3 text-2xl font-bold text-white">{{ $waterStatus['action'] }}</p>
                <p class="mt-2 text-sm text-slate-300">Aliran air mengikuti logika aman / proses berdasarkan status kualitas air.</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-[2rem] border border-white/10 bg-slate-900/80 p-6 ring-1 ring-white/5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm text-slate-400">Data sensor</p>
                        <h2 class="text-2xl font-bold text-white">Pembacaan terbaru</h2>
                    </div>
                    <span class="rounded-full bg-sky-500/15 px-3 py-1 text-xs font-semibold text-sky-300">Live / latest</span>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">pH air</p>
                        <p class="mt-3 text-3xl font-bold text-white">{{ data_get($sensor, 'ph_air') !== null ? number_format((float) data_get($sensor, 'ph_air'), 1) : '-' }}</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">pH tanah</p>
                        <p class="mt-3 text-3xl font-bold text-white">{{ data_get($sensor, 'ph_tanah') !== null ? number_format((float) data_get($sensor, 'ph_tanah'), 1) : '-' }}</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Kelembaban tanah</p>
                        <p class="mt-3 text-3xl font-bold text-white">{{ data_get($sensor, 'kelembaban_tanah') !== null ? number_format((float) data_get($sensor, 'kelembaban_tanah'), 0) : '-' }}%</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Suhu udara</p>
                        <p class="mt-3 text-3xl font-bold text-white">{{ data_get($sensor, 'suhu_udara') !== null ? number_format((float) data_get($sensor, 'suhu_udara'), 1) : '-' }}°C</p>
                    </div>
                </div>

                <div class="mt-6 rounded-3xl border border-white/10 bg-white/5 p-5">
                    <p class="text-sm font-semibold text-white">Logika air otomatis</p>
                    <p class="mt-2 text-sm leading-7 text-slate-300">Ketika status air aman, sistem akan meneruskan air ke lahan. Jika belum aman, air akan diproses terlebih dahulu agar kondisi lahan tetap stabil.</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
                    <p class="text-sm text-slate-400">Rekomendasi aktif</p>
                    <h3 class="mt-2 text-2xl font-bold text-white">{{ $bestRecommendation['name'] ?? 'Belum tersedia' }}</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-300">{{ $bestRecommendation['description'] ?? 'Sistem belum membaca data cukup untuk rekomendasi saat ini.' }}</p>
                </div>

                <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
                    <p class="text-sm text-slate-400">Spreadsheet</p>
                    <p class="mt-2 text-xl font-semibold text-white">{{ data_get($spreadsheetRecommendation, 'rekomendasi_tanaman', '-') }}</p>
                    <p class="mt-2 text-sm text-slate-300">Catatan: {{ data_get($spreadsheetRecommendation, 'catatan', '-') ?? '-' }}</p>
                </div>

                <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
                    <p class="text-sm text-slate-400">Aksi lanjutan</p>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <a href="{{ route('recommendation') }}" class="rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400">Ganti tanaman</a>
                        <a href="{{ route('dashboard') }}" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-medium text-slate-100 transition hover:bg-white/10">Kembali ke dashboard</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTROL PANEL -->
        <div class="rounded-[2rem] border border-white/10 bg-slate-900/80 p-6 ring-1 ring-white/5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400">Kontrol Perangkat</p>
                    <h2 class="text-2xl font-bold text-white">Control IoT</h2>
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                <!-- FILTRASI -->
                <div class="rounded-2xl bg-white/5 p-4 border border-white/10">
                    <p class="text-sm text-slate-400">Filtrasi Air</p>
                    <div class="mt-3 flex gap-2">
                        <button onclick="control('V8',1)" class="flex-1 bg-emerald-500 hover:bg-emerald-400 text-black py-2 rounded-xl font-semibold">ON</button>
                        <button onclick="control('V8',0)" class="flex-1 bg-rose-500 hover:bg-rose-400 text-white py-2 rounded-xl font-semibold">OFF</button>
                    </div>
                </div>

                <!-- PEMANAS -->
                <div class="rounded-2xl bg-white/5 p-4 border border-white/10">
                    <p class="text-sm text-slate-400">Pemanas Nikrom</p>
                    <div class="mt-3 flex gap-2">
                        <button onclick="control('V9',1)" class="flex-1 bg-emerald-500 hover:bg-emerald-400 text-black py-2 rounded-xl font-semibold">ON</button>
                        <button onclick="control('V9',0)" class="flex-1 bg-rose-500 hover:bg-rose-400 text-white py-2 rounded-xl font-semibold">OFF</button>
                    </div>
                </div>

                <!-- GARAM -->
                <div class="rounded-2xl bg-white/5 p-4 border border-white/10">
                    <p class="text-sm text-slate-400">Tambah Garam</p>
                    <button onclick="control('V10',1)" class="mt-3 w-full bg-yellow-400 hover:bg-yellow-300 text-black py-2 rounded-xl font-semibold">
                        Jalankan
                    </button>
                </div>

                <!-- ALIRAN -->
                <div class="rounded-2xl bg-white/5 p-4 border border-white/10">
                    <p class="text-sm text-slate-400">Aliran Air</p>
                    <div class="mt-3 flex gap-2">
                        <button onclick="control('V12',1)" class="flex-1 bg-emerald-500 hover:bg-emerald-400 text-black py-2 rounded-xl font-semibold">ON</button>
                        <button onclick="control('V12',0)" class="flex-1 bg-rose-500 hover:bg-rose-400 text-white py-2 rounded-xl font-semibold">OFF</button>
                    </div>
                </div>

                <!-- SERVO -->
                <div class="rounded-2xl bg-white/5 p-4 border border-white/10">
                    <p class="text-sm text-slate-400">Servo Valve</p>
                    <div class="mt-3 flex gap-2">
                        <button onclick="control('V13',1)" class="flex-1 bg-sky-500 hover:bg-sky-400 text-white py-2 rounded-xl font-semibold">Buka</button>
                        <button onclick="control('V13',0)" class="flex-1 bg-rose-500 hover:bg-rose-400 text-white py-2 rounded-xl font-semibold">Tutup</button>
                    </div>
                </div>

                <!-- PENYIRAMAN -->
                <div class="rounded-2xl bg-white/5 p-4 border border-white/10">
                    <p class="text-sm text-slate-400">Penyiraman Ulang</p>
                    <button onclick="control('V16',1)" class="mt-3 w-full bg-indigo-500 hover:bg-indigo-400 text-white py-2 rounded-xl font-semibold">
                        Jalankan
                    </button>
                </div>

            </div>
        </div>

        <div class="rounded-[2rem] border border-white/10 bg-slate-900/80 p-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-white">Analisis AI</h2>
                <button type="button" onclick="runAI(event)" class="bg-indigo-500 hover:bg-indigo-400 px-4 py-2 rounded-xl text-white font-semibold">
                    Jalankan AI
                </button>
            </div>

            <div id="ai-result" class="mt-4 text-sm text-slate-300 whitespace-pre-line">
                Belum ada analisis
            </div>
        </div>
    </section>
<script>
function control(pin, value) {
    fetch("{{ route('control') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            pin: pin,
            value: value
        })
    })
    .then(res => res.json())
    .then(data => {
        alert("Status: " + data.status);
    })
    .catch(err => {
        alert("Gagal kirim perintah");
        console.error(err);
    });
}

function runAI(event) {
    const resultElement = document.getElementById('ai-result');
    const button = event.target;
    
    resultElement.innerText = "⏳ Memproses analisis AI... Mohon tunggu sebentar...";
    button.disabled = true;
    button.style.opacity = '0.6';

    fetch("{{ route('ai.analysis') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        }
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        resultElement.innerText = data.result || "Tidak ada hasil analisis";
    })
    .catch((error) => {
        resultElement.innerText = "❌ Gagal mengambil data AI:\n" + error.message + "\n\nMohon pastikan API key OpenRouter sudah dikonfigurasi dengan benar di .env";
        console.error('Error:', error);
    })
    .finally(() => {
        button.disabled = false;
        button.style.opacity = '1';
    });
}
</script>
@endsection
