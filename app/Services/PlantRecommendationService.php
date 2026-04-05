<?php

namespace App\Services;

use App\Models\SensorData;
use Illuminate\Support\Str;

class PlantRecommendationService
{
    private function plants(): array
    {
        return config('smartfarming.plants', []);
    }

    private function sensorValue(SensorData|array|null $sensor, string $key): mixed
    {
        return data_get($sensor, $key);
    }

    private function normalizedWaterStatus(mixed $sensor): string
    {
        $condition = Str::of((string) $this->sensorValue($sensor, 'kondisi_air'))->lower()->trim()->toString();

        if ($condition === '') {
            return 'belum_terbaca';
        }

        if (Str::contains($condition, ['aman', 'baik', 'jernih', 'layak', 'normal', 'siap'])) {
            return 'aman';
        }

        return 'perlu_pemrosesan';
    }

    public function recommend(SensorData|array|null $sensor = null, ?array $spreadsheetRecommendation = null): array
    {
        $plants = $this->plants();
        $sheetName = Str::of((string) data_get($spreadsheetRecommendation, 'rekomendasi_tanaman', ''))
            ->lower()
            ->trim()
            ->toString();
        $waterState = $this->normalizedWaterStatus($sensor);

        $results = [];

        foreach ($plants as $key => $plant) {
            $score = 50;
            $reasons = [];

            $ph = $this->sensorValue($sensor, 'ph_tanah');
            if ($ph === null) {
                $ph = $this->sensorValue($sensor, 'ph_air');
            }

            if (is_numeric($ph)) {
                $mid = ((float) $plant['ideal_ph_min'] + (float) $plant['ideal_ph_max']) / 2;
                $gap = abs((float) $ph - $mid);
                $phScore = max(0, 40 - ($gap * 12));
                $score += $phScore;

                $reasons[] = 'pH sensor berada di sekitar rentang ideal ' . $plant['ideal_ph_min'] . '–' . $plant['ideal_ph_max'] . '.';
            }

            $temperature = $this->sensorValue($sensor, 'suhu_udara');
            if (is_numeric($temperature)) {
                $score += ((float) $temperature >= 20 && (float) $temperature <= 34) ? 8 : 0;
            }

            $humidity = $this->sensorValue($sensor, 'kelembaban_tanah');
            if (is_numeric($humidity)) {
                $score += (float) $humidity >= 40 ? 6 : 0;
            }

            if ($waterState === 'aman') {
                $score += in_array($plant['water_need'], ['tinggi', 'sedang'], true) ? 10 : 5;
                $reasons[] = 'Kondisi air terdeteksi aman sehingga cocok untuk pemanfaatan lahan.';
            } elseif ($waterState === 'perlu_pemrosesan') {
                $score -= 5;
                $reasons[] = 'Kondisi air masih perlu diproses sebelum dialirkan ke lahan.';
            }

            if ($sheetName !== '' && Str::contains(Str::lower($plant['name']), $sheetName)) {
                $score += 20;
                $reasons[] = 'Selaras dengan rekomendasi dari spreadsheet.';
            }

            $results[] = [
                'key' => $key,
                'name' => $plant['name'],
                'description' => $plant['description'],
                'advantages' => $plant['advantages'],
                'ideal_ph' => $plant['ideal_ph_min'] . ' - ' . $plant['ideal_ph_max'],
                'ideal_environment' => $plant['ideal_environment'],
                'badge' => $plant['badge'],
                'water_need' => $plant['water_need'],
                'score' => (int) round($score),
                'reasons' => array_values(array_unique($reasons)),
            ];
        }

        usort($results, fn ($a, $b) => $b['score'] <=> $a['score']);

        return [
            'best' => $results[0] ?? null,
            'items' => array_slice($results, 0, 4),
            'water_state' => $waterState,
            'sheet' => $spreadsheetRecommendation,
        ];
    }
}
