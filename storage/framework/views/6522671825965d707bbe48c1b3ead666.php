<?php $__env->startSection('title', $site['name'] . ' • Login'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mx-auto grid max-w-5xl gap-8 lg:grid-cols-[0.95fr_1.05fr] lg:items-center">
        <div class="space-y-6">
            <span class="inline-flex rounded-full border border-emerald-500/20 bg-emerald-500/10 px-4 py-2 text-sm text-emerald-200">
                Akses khusus user terverifikasi
            </span>
            <h1 class="text-4xl font-bold tracking-tight text-white">
                Masuk untuk memantau lahan dan tanaman aktif.
            </h1>
            <p class="max-w-xl text-lg leading-8 text-slate-300">
                Login dipakai agar sesi user tersimpan. Setelah masuk, user akan diarahkan ke dashboard
                untuk melihat kondisi lahan, kualitas air, dan rekomendasi tanaman.
            </p>
        </div>

        <div class="rounded-[2rem] border border-white/10 bg-slate-900/80 p-8 shadow-2xl shadow-emerald-950/30 ring-1 ring-white/5">
            <h2 class="text-2xl font-bold text-white">Login dashboard</h2>
            <p class="mt-2 text-sm text-slate-400">
                Gunakan email dan kata sandi yang telah terdaftar.
            </p>

            <form method="POST" action="<?php echo e(route('login.perform')); ?>" class="mt-8 space-y-5">
                <?php echo csrf_field(); ?>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-200">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="<?php echo e(old('email')); ?>" 
                        required 
                        autofocus 
                        class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-slate-100 outline-none ring-0 transition placeholder:text-slate-500 focus:border-emerald-500/40 focus:ring-2 focus:ring-emerald-500/20" 
                        placeholder="bismillahkrenova@gmail.com"
                    >
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-rose-300"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-200">Kata sandi</label>
                    <input 
                        type="password" 
                        name="password" 
                        required 
                        class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-slate-100 outline-none ring-0 transition placeholder:text-slate-500 focus:border-emerald-500/40 focus:ring-2 focus:ring-emerald-500/20" 
                        placeholder="bismillah_Krenov4"
                    >
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-rose-300"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <label class="flex items-center gap-3 text-sm text-slate-300">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-white/20 bg-slate-900 text-emerald-500 focus:ring-emerald-500/20">
                    Ingat sesi saya
                </label>

                <button type="submit" class="w-full rounded-2xl bg-emerald-500 px-5 py-3 font-semibold text-slate-950 transition hover:bg-emerald-400">
                    Masuk ke Dashboard
                </button>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\smart_sabin\resources\views/auth/login.blade.php ENDPATH**/ ?>