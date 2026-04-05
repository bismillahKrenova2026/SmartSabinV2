<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', config('smartfarming.site.name', config('app.name'))); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { color-scheme: dark; }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="fixed inset-0 -z-10 bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_45%),radial-gradient(circle_at_bottom,_rgba(59,130,246,0.12),_transparent_40%)]"></div>

    <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/80 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-3">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="h-10 w-10 rounded-2xl object-contain">
                <div>
                    <p class="text-sm font-semibold tracking-[0.25em] text-emerald-300 uppercase">Smart Sabin</p>
                    <p class="text-xs text-slate-400">Smart farming monitoring</p>
                </div>
            </a>

            <nav class="hidden items-center gap-6 text-sm text-slate-300 md:flex">
                <a href="<?php echo e(route('home')); ?>" class="transition hover:text-white">Beranda</a>
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="transition hover:text-white">Dashboard</a>
                    <a href="<?php echo e(route('recommendation')); ?>" class="transition hover:text-white">Rekomendasi</a>
                    <a href="<?php echo e(route('monitoring')); ?>" class="transition hover:text-white">Monitoring</a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="transition hover:text-white">Masuk</a>
                <?php endif; ?>
            </nav>

            <div class="flex items-center gap-3">
                <?php if(auth()->guard()->check()): ?>
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-medium text-white"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-xs text-slate-400"><?php echo e(auth()->user()->email); ?></p>
                    </div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-slate-200 transition hover:bg-white/10">
                            Keluar
                        </button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400">
                        Masuk
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <?php if(session('status')): ?>
        <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                <?php echo e(session('status')); ?>

            </div>
        </div>
    <?php endif; ?>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="border-t border-white/10 py-6 text-center text-sm text-slate-500">
        © <?php echo e(date('Y')); ?> Smart Sabin • Sistem monitoring pertanian berbasis IoT
    </footer>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\laragon\www\smart_sabin\resources\views/layouts/app.blade.php ENDPATH**/ ?>