<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIService
{
    public function analyze($sensor)
{
    $apiKey = config('services.openrouter.key');
    $model = config('services.openrouter.model');

    // buat prompt
    $prompt = "...";

    // 🔥 DI SINI LETAK KODE KAMU
    $response = Http::withHeaders([
        'Authorization' => "Bearer $apiKey",
        'HTTP-Referer' => config('app.url'),
        'X-Title' => 'Smart Sabin',
        'Content-Type' => 'application/json'
    ])->post('https://openrouter.ai/api/v1/chat/completions', [
        'model' => $model,
        'messages' => [
            [
                'role' => 'user',
                'content' => $prompt
            ]
        ],
        'temperature' => 0.7
    ]);

    if (!$response->successful()) {
        return "ERROR " . $response->status() . "\n" . $response->body();
    }

    return $response->json()['choices'][0]['message']['content'] ?? 'Tidak ada respon';
}
}