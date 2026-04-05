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
            ?? $lower['tanaman']
            ?? $lower['plant']
            ?? $lower['name']
            ?? null;

        $statusServo = $lower['status_servo']
            ?? $lower['servo']
            ?? $lower['servo_valve']
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
            'timestamp' => $lower['timestamp'] ?? $lower['created_at'] ?? $lower['waktu'] ?? null,
            'raw' => $row,
        ];
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
            $candidate = collect($payload)->last();

            if (is_array($candidate)) {
                return $this->normalizeRow($candidate);
            }

            return null;
        }

        return $this->normalizeRow($payload);
    }

    public function latestRecommendation(): ?array
    {
        return Cache::remember('smart_sabin.spreadsheet.latest_recommendation', now()->addSeconds(30), function () {
            $url = $this->url();

            if (! $url) {
                return null;
            }

            $response = Http::withoutVerifying()
                ->acceptJson()
                ->timeout(15)
                ->get($url);

            if (! $response->successful()) {
                return null;
            }

            return $this->extractLatest($response->json());
        });
    }
}
