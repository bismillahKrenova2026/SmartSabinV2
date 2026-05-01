<?php

namespace App\Services;

use App\Models\SensorData;
use Illuminate\Support\Str;

class WaterProcessingService
{
    public function evaluate(SensorData|array|null $sensor = null): array
    {
        $condition = Str::of((string) data_get($sensor, 'kondisi_air'))->lower()->trim()->toString();
        $phAir = data_get($sensor, 'ph_air');
        $phTanah = data_get($sensor, 'ph_tanah');
        $ph = is_numeric($phAir) ? (float) $phAir : (is_numeric($phTanah) ? (float) $phTanah : null);

        $safeWords = ['aman', 'baik', 'jernih', 'layak', 'normal', 'siap'];
        $safeCondition = $condition !== '' && Str::contains($condition, $safeWords);
        $safePh = $ph !== null && $ph >= 5.5 && $ph <= 7.5;

        if ($sensor === null) {
            return [
                'status' => 'belum_ada_data',
                'label' => 'Belum ada data',
                'action' => 'Menunggu pembacaan sensor',
                'description' => 'Sistem masih menunggu data terbaru dari Blynk.',
                'badge' => 'bg-slate-700 text-slate-200',
            ];
        }

        if ($safeCondition || $safePh) {
            return [
                'status' => 'aman',
                'label' => 'Aman',
                'action' => 'Air diteruskan ke lahan',
                'description' => 'Kondisi air masuk kategori aman sehingga dapat dialirkan ke lahan.',
                'badge' => 'bg-emerald-500/15 text-emerald-300 ring-1 ring-emerald-500/30',
            ];
        }

        return [
            'status' => 'perlu_diproses',
            'label' => 'Perlu diproses',
            'action' => 'Air diproses terlebih dahulu',
            'description' => 'Sistem menahan aliran dan menjalankan pengolahan air sebelum diteruskan.',
            'badge' => 'bg-amber-500/15 text-amber-300 ring-1 ring-amber-500/30',
        ];
    }
}
