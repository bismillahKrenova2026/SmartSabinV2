<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SpreadsheetService
{
    private function url(): ?string
    {
        $url = config('services.google_sheet.web_app_url');

        return filled($url) ? $url : null;
    }

    private function normalizeRow(array $row): array
    {
        $lower = [];

        foreach ($row as $key => $value) {
            $normalizedKey = Str::of((string) $key)
                ->lower()
                ->replace([' ', '-'], '_')
                ->toString();

            $lower[$normalizedKey] = is_string($value) ? trim($value) : $value;
        }

        $recommendation = $lower['rekomendasi_tanaman']
            ?? $lower['rekomendasi']
            ?? $lower['keterangan']
            ?? $lower['saran_tindakan']
            ?? $lower['tanaman']
            ?? $lower['plant']
            ?? $lower['name']
            ?? null;

        $statusServo = $lower['status_servo']
            ?? $lower['servo']
            ?? $lower['servo_valve']
            ?? $lower['saran_status_servo']
            ?? $lower['status']
            ?? null;

        $targetPh = $lower['target_ph']
            ?? $lower['targetph']
            ?? $lower['ph_target']
            ?? $lower['ph_target_tanaman']
            ?? null;

        return [
            'rekomendasi_tanaman' => $recommendation,
            'status_servo' => $statusServo,
            'target_ph' => $targetPh,
            'catatan' => $lower['catatan'] ?? $lower['note'] ?? null,
            'timestamp' => $lower['timestamp'] ?? $lower['created_at'] ?? $lower['waktu'] ?? $lower['time'] ?? null,
            'raw' => $row,
        ];
    }

    private function parseRowTimestamp(array $row): ?int
    {
        $timestamp = $row['timestamp'] ?? $row['created_at'] ?? $row['waktu'] ?? $row['time'] ?? null;

        if (! $timestamp) {
            return null;
        }

        try {
            return (new \DateTime((string) $timestamp))->getTimestamp();
        } catch (\Exception $e) {
            return null;
        }
    }

    private function pickLatestRow(array $rows): ?array
    {
        $candidates = collect($rows)
            ->filter(fn ($row) => is_array($row))
            ->map(fn ($row) => $this->normalizeRow($row));

        if ($candidates->isEmpty()) {
            return null;
        }

        $withTimestamp = $candidates->filter(fn (array $row) => $this->parseRowTimestamp($row) !== null);
        if ($withTimestamp->isNotEmpty()) {
            return $withTimestamp
                ->sortByDesc(fn (array $row) => $this->parseRowTimestamp($row) ?? 0)
                ->first();
        }

        return $candidates->last();
    }

    private function extractLatest(array|object|null $payload): ?array
    {
        if ($payload === null) {
            return null;
        }

        $payload = json_decode(json_encode($payload), true);

        if (! is_array($payload) || empty($payload)) {
            return null;
        }

        if (isset($payload['data']) && is_array($payload['data'])) {
            $payload = $payload['data'];
        } elseif (isset($payload['rows']) && is_array($payload['rows'])) {
            $payload = $payload['rows'];
        } elseif (isset($payload['recommendations']) && is_array($payload['recommendations'])) {
            $payload = $payload['recommendations'];
        }

        if (array_is_list($payload)) {
            return $this->pickLatestRow($payload);
        }

        return $this->normalizeRow($payload);
    }

    public function latestRecommendation(): ?array
    {
        return Cache::remember('farmonitor.spreadsheet.latest_recommendation', now()->addSeconds(10), function () {
            $url = $this->url();

            if (! $url) {
                return null;
            }

            try {
                $response = Http::withoutVerifying()
                    ->acceptJson()
                    ->timeout(15)
                    ->get($url);

                if (! $response->successful()) {
                    return null;
                }

                return $this->extractLatest($response->json());
            } catch (\Exception $e) {
                // Jika koneksi gagal, return null agar aplikasi tetap berfungsi
                return null;
            }
        });
    }
}
