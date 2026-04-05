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

        $response = Http::timeout(10)->get($url);

        if (! $response->successful()) return null;

        $body = trim((string) $response->body());

        return $body === '' ? null : $body;
    }

    public function setPin(string $pin, $value): bool
    {
        $token = $this->token();

        if (! $token) return false;

        $url = 'https://blynk.cloud/external/api/update?token='
            . urlencode($token)
            . '&' . $pin . '=' . urlencode($value);

        $response = Http::timeout(10)->get($url);

        return $response->successful();
    }

    private function toFloat(?string $value): ?float
    {
        if ($value === null) return null;

        $normalized = str_replace(',', '.', trim($value));

        return is_numeric($normalized) ? (float) $normalized : null;
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

    public function snapshot(): ?array
    {
        return Cache::remember('smart_sabin.blynk.snapshot', now()->addSeconds(5), function () {
            $payload = [
                'status_filtrasi' => $this->toBoolean($this->getPin('V8')),
                'pemanas_nikrom' => $this->toBoolean($this->getPin('V9')),
                'tambah_garam' => $this->toBoolean($this->getPin('V10')),
                'status_aliran' => $this->toBoolean($this->getPin('V12')),
                'servo_valve' => $this->toFloat($this->getPin('V13')),
                'penyiraman_ulang' => $this->toBoolean($this->getPin('V16')),
            ];

            return collect($payload)->contains(fn ($v) => $v !== null) ? $payload : null;
        });
    }
}