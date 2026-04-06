<?php $__env->startSection('title', $site['name'] . ' • Rekomendasi Tanaman'); ?>

<?php $__env->startSection('content'); ?>
    <section class="space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-300">Pemilihan tanaman</p>
                    <span class="rounded-full bg-indigo-500/20 px-3 py-1 text-xs font-semibold text-indigo-300">🤖 Powered by AI</span>
                </div>
                <h1 class="mt-2 text-3xl font-bold text-white">Pilih tanaman yang paling cocok untuk lahan saat ini</h1>
                <p class="mt-3 max-w-3xl text-slate-300">Sistem AI menganalisis kondisi lahan, kualitas air, dan data sensor untuk memberikan rekomendasi tanaman yang paling akurat. User bisa memilih satu tanaman aktif lalu monitoring akan mengikutinya.</p>
            </div>
            <div class="flex gap-3">
                <a href="<?php echo e(route('dashboard')); ?>" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-medium text-slate-100 transition hover:bg-white/10">Kembali ke dashboard</a>
                <a href="<?php echo e(route('monitoring')); ?>" class="rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400">Ke monitoring</a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
                <p class="text-sm text-slate-400">Saran spreadsheet</p>
                <p class="mt-3 text-2xl font-bold text-white"><?php echo e(data_get($spreadsheetRecommendation, 'rekomendasi_tanaman', '-')); ?></p>
                <p class="mt-2 text-sm text-slate-300">Target pH: <?php echo e(data_get($spreadsheetRecommendation, 'target_ph', '-')); ?></p>
            </div>
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
                <p class="text-sm text-slate-400">Tanaman aktif</p>
                <p class="mt-3 text-2xl font-bold text-white"><?php echo e($activePlantKey ? data_get($plants, $activePlantKey . '.name') : 'Belum dipilih'); ?></p>
                <p class="mt-2 text-sm text-slate-300">User bisa mengganti pilihan kapan saja.</p>
            </div>
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
                <p class="text-sm text-slate-400">Status air</p>
                <span class="mt-3 inline-flex rounded-full px-3 py-1 text-xs font-semibold <?php echo e($waterStatus['badge']); ?>"><?php echo e($waterStatus['label']); ?></span>
                <p class="mt-2 text-sm text-slate-300"><?php echo e($waterStatus['action']); ?></p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <?php $__currentLoopData = $recommendedPlants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="rounded-[2rem] border border-white/10 bg-slate-900/80 p-6 ring-1 ring-white/5 <?php echo e($activePlantKey === $plant['key'] ? 'ring-2 ring-emerald-500/40' : ''); ?>">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-semibold text-emerald-300"><?php echo e($plant['badge']); ?></span>
                            <h2 class="mt-4 text-2xl font-bold text-white"><?php echo e($plant['name']); ?></h2>
                            <p class="mt-2 text-sm text-slate-400">pH ideal: <?php echo e($plant['ideal_ph']); ?></p>
                        </div>
                        <span class="rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-sm font-semibold text-white">Skor <?php echo e($plant['score']); ?></span>
                    </div>

                    <p class="mt-4 leading-7 text-slate-300"><?php echo e($plant['description']); ?></p>

                    <?php if(! empty($plant['reasons'])): ?>
                        <div class="mt-5 rounded-3xl border border-white/10 bg-white/5 p-5">
                            <p class="text-sm font-semibold text-white">Alasan sistem merekomendasikan</p>
                            <ul class="mt-3 space-y-2 text-sm text-slate-300">
                                <?php $__currentLoopData = $plant['reasons']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="flex gap-2"><span class="text-emerald-400">•</span><span><?php echo e($reason); ?></span></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if(! empty($plant['device_settings'])): ?>
                        <div class="mt-5 rounded-3xl border border-indigo-500/20 bg-indigo-500/10 p-5">
                            <p class="text-sm font-semibold text-indigo-300">🔧 Saran Pengaturan Perangkat</p>
                            <ul class="mt-3 space-y-2 text-sm text-slate-300">
                                <?php $__currentLoopData = $plant['device_settings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="flex gap-2"><span class="text-indigo-400">⚙</span><span><?php echo e($setting); ?></span></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if(! empty($plant['monitoring_notes'])): ?>
                        <div class="mt-5 rounded-3xl border border-sky-500/20 bg-sky-500/10 p-5">
                            <p class="text-sm font-semibold text-sky-300">📋 Catatan Monitoring</p>
                            <p class="mt-2 text-sm text-slate-300"><?php echo e($plant['monitoring_notes']); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="mt-5">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Keunggulan</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <?php $__currentLoopData = $plant['advantages']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $advantage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-200"><?php echo e($advantage); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <form method="POST" action="<?php echo e(route('plant.select')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="plant_key" value="<?php echo e($plant['key']); ?>">
                            <button class="rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400">
                                Pilih tanaman ini
                            </button>
                        </form>
                        <a href="<?php echo e(route('monitoring')); ?>" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-medium text-slate-100 transition hover:bg-white/10">Lihat monitoring</a>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm text-slate-400">Tindakan cepat</p>
                    <?php if($activePlantKey): ?>
                        <h2 class="mt-1 text-2xl font-bold text-white">Jika pilihan sebelumnya kurang tepat, reset tanaman aktif</h2>
                    <?php else: ?>
                        <h2 class="mt-1 text-2xl font-bold text-white">Mulai dengan memilih salah satu tanaman di atas</h2>
                    <?php endif; ?>
                </div>
                <?php if($activePlantKey): ?>
                    <form method="POST" action="<?php echo e(route('plant.change')); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="rounded-2xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-200 transition hover:bg-rose-500/20">
                            Ganti tanaman
                        </button>
                    </form>
                <?php else: ?>
                    <div class="flex gap-3">
                        <a href="#top" class="rounded-2xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400">
                            Pilih tanaman
                        </a>
                        <a href="<?php echo e(route('monitoring')); ?>" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-medium text-slate-100 transition hover:bg-white/10">
                            Lihat monitoring
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\smart_sabin\resources\views/recommendation.blade.php ENDPATH**/ ?>