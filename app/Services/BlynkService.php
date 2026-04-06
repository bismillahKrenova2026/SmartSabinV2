<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BlynkService
{
    private function token(): ?string
    {
        $token = config('services.blynk.token');
        return filled($token) ? $token : null;
    }

    private function getPin(string $pin): ?string
    {
        $token = $this->token();

        if (! $token) return null;

        $url = 'https://blynk.cloud/external/api/get?token=' . urlencode($token) . '&' . $pin;

        try {
            $response = Http::timeout(10)->get($url);
            if (! $response->successful()) return null;
            $body = trim((string) $response->body());
            return $body === '' ? null : $body;
        } catch (\Exception $e) {
            // Jika koneksi gagal, return null
            return null;
        }
    }

    public function setPin(string $pin, $value): bool
    {
        $token = $this->token();

        if (! $token) return false;

        $url = 'https://blynk.cloud/external/api/update?token='
            . urlencode($token)
            . '&' . $pin . '=' . urlencode($value);

        try {
            $response = Http::timeout(10)->get($url);
            return $response->successful();
        } catch (\Exception $e) {
            // Jika koneksi gagal, return false
            return false;
        }
    }

    private function toFloat(?string $value): ?float
    {
        if ($value === null) return null;

        $normalized = str_replace(',', '.', trim($value));

        return is_numeric($normalized) ? (float) $normalized : null;
    }

    private function toInteger(?string $value): ?int
    {
        if ($value === null) return null;

        $normalized = str_replace(',', '.', trim($value));
        return is_numeric($normalized) ? (int) round((float) $normalized) : null;
    }

    private function toBoolean(?string $value): ?bool
    {
        if ($value === null) return null;

        $normalized = strtolower(trim($value));

        return match ($normalized) {
            '1','true','on','yes','aktif','open' => true,
            '0','false','off','no','nonaktif','closed' => false,
            default => null,
        };
    }

    public function clearSnapshotCache(): void
    {
        Cache::forget('smart_sabin.blynk.snapshot');
    }

    public function snapshot(bool $fresh = false): ?array
    {
        if ($fresh) {
            $this->clearSnapshotCache();
        }

        return Cache::remember('smart_sabin.blynk.snapshot', now()->addSeconds(3), function () {
            $payload = [
                'ph_air' => $this->toFloat($this->getPin('V0')),
                'ph_tanah' => $this->toFloat($this->getPin('V1')),
                'kelembaban_tanah' => $this->toFloat($this->getPin('V2')),
                'suhu_udara' => $this->toFloat($this->getPin('V3')),
                'kelembaban_udara' => $this->toFloat($this->getPin('V4')),
                'intensitas_cahaya' => $this->toFloat($this->getPin('V5')),
                'sensor_hujan' => $this->toBoolean($this->getPin('V6')),
                'kondisi_air' => $this->getPin('V7'),
                'status_filtrasi' => $this->toBoolean($this->getPin('V8')),
                'pemanas_nikrom' => $this->toBoolean($this->getPin('V9')),
                'tambah_garam' => $this->toBoolean($this->getPin('V10')),
                'target_ph_tanaman' => $this->toFloat($this->getPin('V11')),
                'status_aliran' => $this->toBoolean($this->getPin('V12')),
                'servo_valve' => $this->toInteger($this->getPin('V13')),
                'target_ph' => $this->toFloat($this->getPin('V14')),
                'ph_stlh_air' => $this->toFloat($this->getPin('V15')),
                'penyiraman_ulang' => $this->toBoolean($this->getPin('V16')),
            ];

            return collect($payload)->contains(fn ($v) => $v !== null) ? $payload : null;
        });
    }
}