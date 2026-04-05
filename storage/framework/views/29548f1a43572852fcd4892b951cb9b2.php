<?php $__env->startSection('title', $site['name'] . ' • Monitoring'); ?>

<?php $__env->startSection('content'); ?>
    <section class="space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-300">Monitoring tanaman aktif</p>
                <h1 class="mt-2 text-3xl font-bold text-white">Pantau tanaman pilihan dan kondisi air secara bersamaan</h1>
                <p class="mt-3 max-w-3xl text-slate-300">Halaman ini menunjukkan tanaman yang sudah dipilih user. Jika air aman, sistem meneruskan air ke lahan. Jika belum aman, air diproses terlebih dahulu.</p>
            </div>
            <div class="flex gap-3">
                <a href="<?php echo e(route('recommendation')); ?>" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-medium text-slate-100 transition hover:bg-white/10">Ubah tanaman</a>
                <form method="POST" action="<?php echo e(route('sensor.sync')); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400">Tarik data terbaru</button>
                </form>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
                <p class="text-sm text-slate-400">Tanaman aktif</p>
                <h2 class="mt-3 text-2xl font-bold text-white"><?php echo e($activePlant['name'] ?? 'Belum ada tanaman aktif'); ?></h2>
                <p class="mt-2 text-sm text-slate-300"><?php echo e($activePlant['description'] ?? 'Pilih tanaman dari halaman rekomendasi untuk mengunci monitoring pada satu tanaman.'); ?></p>
            </div>
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
                <p class="text-sm text-slate-400">Status air</p>
                <span class="mt-3 inline-flex rounded-full px-3 py-1 text-xs font-semibold <?php echo e($waterStatus['badge']); ?>"><?php echo e($waterStatus['label']); ?></span>
                <p class="mt-3 text-sm leading-7 text-slate-300"><?php echo e($waterStatus['description']); ?></p>
            </div>
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
                <p class="text-sm text-slate-400">Aksi sistem</p>
                <p class="mt-3 text-2xl font-bold text-white"><?php echo e($waterStatus['action']); ?></p>
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
                        <p class="mt-3 text-3xl font-bold text-white"><?php echo e(data_get($sensor, 'ph_air') !== null ? number_format((float) data_get($sensor, 'ph_air'), 1) : '-'); ?></p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">pH tanah</p>
                        <p class="mt-3 text-3xl font-bold text-white"><?php echo e(data_get($sensor, 'ph_tanah') !== null ? number_format((float) data_get($sensor, 'ph_tanah'), 1) : '-'); ?></p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Kelembaban tanah</p>
                        <p class="mt-3 text-3xl font-bold text-white"><?php echo e(data_get($sensor, 'kelembaban_tanah') !== null ? number_format((float) data_get($sensor, 'kelembaban_tanah'), 0) : '-'); ?>%</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Suhu udara</p>
                        <p class="mt-3 text-3xl font-bold text-white"><?php echo e(data_get($sensor, 'suhu_udara') !== null ? number_format((float) data_get($sensor, 'suhu_udara'), 1) : '-'); ?>°C</p>
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
                    <h3 class="mt-2 text-2xl font-bold text-white"><?php echo e($bestRecommendation['name'] ?? 'Belum tersedia'); ?></h3>
                    <p class="mt-3 text-sm leading-7 text-slate-300"><?php echo e($bestRecommendation['description'] ?? 'Sistem belum membaca data cukup untuk rekomendasi saat ini.'); ?></p>
                </div>

                <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
                    <p class="text-sm text-slate-400">Spreadsheet</p>
                    <p class="mt-2 text-xl font-semibold text-white"><?php echo e(data_get($spreadsheetRecommendation, 'rekomendasi_tanaman', '-')); ?></p>
                    <p class="mt-2 text-sm text-slate-300">Catatan: <?php echo e(data_get($spreadsheetRecommendation, 'catatan', '-') ?? '-'); ?></p>
                </div>

                <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
                    <p class="text-sm text-slate-400">Aksi lanjutan</p>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <a href="<?php echo e(route('recommendation')); ?>" class="rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400">Ganti tanaman</a>
                        <a href="<?php echo e(route('dashboard')); ?>" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-medium text-slate-100 transition hover:bg-white/10">Kembali ke dashboard</a>
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
                <button onclick="runAI()" class="bg-indigo-500 hover:bg-indigo-400 px-4 py-2 rounded-xl text-white font-semibold">
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
    fetch("<?php echo e(route('control')); ?>", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
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

function runAI() {
    document.getElementById('ai-result').innerText = "Memproses AI...";

    fetch("<?php echo e(route('ai.analysis')); ?>", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
        }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('ai-result').innerText = data.result;
    })
    .catch(() => {
        document.getElementById('ai-result').innerText = "Gagal mengambil data AI";
    });
}
</script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\smart_sabin\resources\views/monitoring.blade.php ENDPATH**/ ?>