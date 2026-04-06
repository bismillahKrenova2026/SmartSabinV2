<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AIService
{
    public function analyze($sensor)
    {
        $apiKey = config('services.openrouter.key');
        $model = config('services.openrouter.model');

        if (! filled($apiKey) || ! filled($model)) {
            return '⚠️ OpenRouter belum dikonfigurasi. Isi OPENROUTER_API_KEY dan OPENROUTER_MODEL di Railway agar analisis AI aktif.';
        }

        // Siapkan data sensor untuk analisis
        $sensorData = [
            'pH Air' => $sensor['ph_air'] ?? 'N/A',
            'pH Tanah' => $sensor['ph_tanah'] ?? 'N/A',
            'Target pH' => $sensor['target_ph'] ?? 'N/A',
            'Kelembaban Tanah' => ($sensor['kelembaban_tanah'] ?? 'N/A') . '%',
            'Kelembaban Udara' => ($sensor['kelembaban_udara'] ?? 'N/A') . '%',
            'Suhu Udara' => ($sensor['suhu_udara'] ?? 'N/A') . '°C',
            'Suhu Air' => ($sensor['suhu_air'] ?? 'N/A') . '°C',
            'Intensitas Cahaya' => $sensor['intensitas_cahaya'] ?? 'N/A',
            'Sensor Hujan' => isset($sensor['sensor_hujan']) ? ($sensor['sensor_hujan'] ? 'Ada' : 'Tidak ada') : 'N/A',
            'Flow Rate' => ($sensor['flow_rate'] ?? 'N/A') . ' L/min',
            'pH Setelah Filtrasi' => $sensor['ph_stlh_air'] ?? 'N/A',
            'Status Filtrasi' => isset($sensor['status_filtrasi']) ? ($sensor['status_filtrasi'] ? 'ON' : 'OFF') : 'N/A',
            'Status Pemanas' => isset($sensor['pemanas_nikrom']) ? ($sensor['pemanas_nikrom'] ? 'ON' : 'OFF') : 'N/A',
            'Status Aliran Air' => isset($sensor['status_aliran']) ? ($sensor['status_aliran'] ? 'Mengalir' : 'Tertutup') : 'N/A',
            'Target pH Tanaman' => $sensor['target_ph_tanaman'] ?? 'N/A',
        ];

        $sensorDataString = '';
        foreach ($sensorData as $key => $value) {
            $sensorDataString .= "- $key: $value\n";
        }

        // Buat prompt analisis komprehensif
        $prompt = "Analisis kondisi sistem pertanian pintar secara real-time berdasarkan data sensor berikut:\n\n$sensorDataString\n\nPERANGKAAT YANG TERSEDIA:\n- Filtrasi Air (V8): Untuk membersihkan air\n- Pemanas Nikrom (V9): Untuk menyesuaikan suhu air\n- Penambah Garam (V10): Untuk penyesuaian pH dan mineral air\n- Aliran Air (V12): Untuk kontrol distribusi air ke lahan\n- Servo Valve (V13): Untuk pengaturan aliran manual\n- Penyiraman Ulang (V16): Untuk sistem penyiraman berulang\n\nBerikan analisis mendetail tentang:\n1. Status kualitas air saat ini (apakah aman untuk tanaman?)\n2. Kondisi tanah dan kelembaban (apakah optimal?)\n3. Kondisi lingkungan (suhu, kelembaban, cahaya)\n4. Status perangkat yang sedang berjalan\n5. REKOMENDASI PERANGKAT: Perangkat mana yang perlu diaktifkan/dinonaktifkan dan alasan mengapa\n6. Tindakan segera yang perlu diambil (jika ada)\n7. Prediksi masalah potensial di masa depan\n\nBerikan respons dalam format yang mudah dipahami, dengan poin-poin jelas dan actionable. Fokus pada saran pengaktifan perangkat untuk optimalisasi kondisi sistem.";

        try {
            $response = Http::timeout(30)->withHeaders([
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
                'temperature' => 0.7,
                'max_tokens' => 1000
            ]);

            if (!$response->successful()) {
                return "❌ Gagal mengambil analisis AI (Status: " . $response->status() . ")\nMohon periksa API key atau coba kembali.";
            }

            return $response->json()['choices'][0]['message']['content'] ?? '❌ Tidak ada respons dari AI';
        } catch (\Exception $e) {
            return "❌ Error: " . $e->getMessage();
        }
    }

    public function recommendPlants($sensor, $spreadsheetRecommendation = null): array
    {
        $apiKey = config('services.openrouter.key');
        $model = config('services.openrouter.model');
        $plants = config('smartfarming.plants', []);

        if (! filled($apiKey) || ! filled($model)) {
            return $this->fallbackRecommendation($sensor, $spreadsheetRecommendation, $plants);
        }

        // Handle sensor kosong
        if (empty($sensor) || !is_array($sensor)) {
            return $this->fallbackRecommendation($sensor, $spreadsheetRecommendation, $plants);
        }

        // Format sensor data
        $sensorInfo = "Status Sensor Lengkap:\n";
        $sensorInfo .= "- pH Tanah: " . ($sensor['ph_tanah'] ?? 'N/A') . "\n";
        $sensorInfo .= "- pH Air: " . ($sensor['ph_air'] ?? 'N/A') . "\n";
        $sensorInfo .= "- pH Air Setelah Filtrasi: " . ($sensor['ph_stlh_air'] ?? 'N/A') . "\n";
        $sensorInfo .= "- Target pH: " . ($sensor['target_ph'] ?? 'N/A') . "\n";
        $sensorInfo .= "- Suhu Udara: " . ($sensor['suhu_udara'] ?? 'N/A') . "°C\n";
        $sensorInfo .= "- Suhu Air: " . ($sensor['suhu_air'] ?? 'N/A') . "°C\n";
        $sensorInfo .= "- Kelembaban Tanah: " . ($sensor['kelembaban_tanah'] ?? 'N/A') . "%\n";
        $sensorInfo .= "- Kelembaban Udara: " . ($sensor['kelembaban_udara'] ?? 'N/A') . "%\n";
        $sensorInfo .= "- Intensitas Cahaya: " . ($sensor['intensitas_cahaya'] ?? 'N/A') . "\n";
        $sensorInfo .= "- Kondisi Air: " . ($sensor['kondisi_air'] ?? 'N/A') . "\n";
        $sensorInfo .= "- Flow Rate: " . ($sensor['flow_rate'] ?? 'N/A') . " L/min\n";
        $sensorInfo .= "\nStatus Perangkat Saat Ini:\n";
        $sensorInfo .= "- Filtrasi Air (V8): " . (($sensor['status_filtrasi'] ?? false) ? 'AKTIF' : 'Tidak aktif') . "\n";
        $sensorInfo .= "- Pemanas Nikrom (V9): " . (($sensor['pemanas_nikrom'] ?? false) ? 'AKTIF' : 'Tidak aktif') . "\n";
        $sensorInfo .= "- Penambah Garam (V10): " . (($sensor['tambah_garam'] ?? false) ? 'AKTIF' : 'Tidak aktif') . "\n";
        $sensorInfo .= "- Aliran Air (V12): " . (($sensor['status_aliran'] ?? false) ? 'MENGALIR' : 'Tertutup') . "\n";
        $sensorInfo .= "- Servo Valve (V13): " . ($sensor['servo_valve'] ?? 'N/A') . "\n";
        $sensorInfo .= "- Penyiraman Ulang (V16): " . (($sensor['penyiraman_ulang'] ?? false) ? 'AKTIF' : 'Tidak aktif') . "\n";

        // Format plants data
        $plantsInfo = "Kandidat Tanaman:\n";
        foreach ($plants as $key => $plant) {
            $plantsInfo .= "\n{$plant['name']} ({$key}):\n";
            $plantsInfo .= "  - Rentang pH ideal: {$plant['ideal_ph_min']} - {$plant['ideal_ph_max']}\n";
            $plantsInfo .= "  - Kebutuhan air: {$plant['water_need']}\n";
            $plantsInfo .= "  - Deskripsi: {$plant['description']}\n";
            $plantsInfo .= "  - Lingkungan ideal: {$plant['ideal_environment']}\n";
        }

        $sheetRecommendation = $spreadsheetRecommendation ? "Rekomendasi dari spreadsheet: " . data_get($spreadsheetRecommendation, 'rekomendasi_tanaman', 'Tidak ada') : "Tidak ada rekomendasi spreadsheet";

        $prompt = <<<PROMPT
Sebagai sistem pertanian pintar, analisis data sensor dan status perangkat lengkap untuk memberikan rekomendasi tanaman yang paling cocok.

$sensorInfo

Spesifikasi Tanaman:
$plantsInfo

$sheetRecommendation

PERANGKAT YANG TERSEDIA UNTUK PENYESUAIAN:
- Filtrasi Air (V8): Untuk membersihkan air sebelum digunakan
- Pemanas Nikrom (V9): Untuk menyesuaikan suhu air (terutama untuk tanaman yang membutuhkan suhu lebih tinggi)
- Penambah Garam (V10): Untuk penyesuaian mineral dan kualitas air
- Aliran Air (V12): Untuk mengontrol distribusi air ke lahan
- Servo Valve (V13): Untuk pengaturan manual aliran/pembukaan-penutupan
- Penyiraman Ulang (V16): Untuk sistem penyiraman berulang jika diperlukan

Berikan rekomendasi dengan output dalam format JSON berikut (HANYA JSON, tanpa penjelasan tambahan):
{
  "recommendations": [
    {
      "key": "nama_key_tanaman",
      "score": skor_0_hingga_100_integer,
      "reasoning": "Penjelasan singkat (1-2 kalimat) mengapa tanaman ini cocok atau tidak cocok",
      "device_settings": {
        "filtration_needed": true/false,
        "heater_needed": true/false,
        "salt_adjuster_needed": true/false,
        "recommended_temp": "angka atau range suhu ideal dalam °C",
        "recommended_ph": "range pH ideal untuk tanaman ini"
      }
    }
  ],
  "best_choice": "key_tanaman_terbaik",
  "water_status_comment": "Komentar singkat tentang status air saat ini",
  "action_plan": {
    "immediate_actions": ["tindakan1", "tindakan2"],
    "device_activation": "Perangkat apa yang perlu diaktifkan dan alasan mengapa",
    "monitoring_notes": "Catatan penting untuk monitoring tanaman ini"
  }
}

Pertimbangkan:
1. Kesesuaian pH sensor dengan pH ideal tanaman (gunakan pemanas/penambah garam jika perlu)
2. Suhu air dan kebutuhan pemanasan untuk optimalisasi
3. Kebutuhan air dengan status kelembaban (gunakan filtrasi jika diperlukan)
4. Saran dari spreadsheet jika ada
5. Kondisi lingkungan (suhu, cahaya, kelembaban)
6. Prioritas pada tanaman yang paling cocok dengan perangkat saat ini

Berikan rating yang jujur dan berbasis data. Urutkan dari score tertinggi ke terendah.
PROMPT;

        try {
            $response = Http::timeout(30)->withHeaders([
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
                'temperature' => 0.5,
                'max_tokens' => 1500
            ]);

            if (!$response->successful()) {
                return $this->fallbackRecommendation($sensor, $spreadsheetRecommendation, $plants);
            }

            $content = $response->json()['choices'][0]['message']['content'] ?? '';

            // Extract JSON dari response
            $jsonMatch = preg_match('/\{[\s\S]*\}/', $content, $matches);
            if (!$jsonMatch) {
                return $this->fallbackRecommendation($sensor, $spreadsheetRecommendation, $plants);
            }

            $aiResult = json_decode($matches[0], true);
            if (!$aiResult || !isset($aiResult['recommendations'])) {
                return $this->fallbackRecommendation($sensor, $spreadsheetRecommendation, $plants);
            }

            return $this->formatRecommendations($aiResult, $plants, $sensor);
        } catch (\Exception $e) {
            return $this->fallbackRecommendation($sensor, $spreadsheetRecommendation, $plants);
        }
    }

    private function formatRecommendations($aiResult, $plants, $sensor): array
    {
        $recommendations = $aiResult['recommendations'] ?? [];
        
        // Urutkan by score descending
        usort($recommendations, fn ($a, $b) => ($b['score'] ?? 0) <=> ($a['score'] ?? 0));

        // Format untuk UI
        $formatted = [];
        foreach (array_slice($recommendations, 0, 4) as $rec) {
            $key = $rec['key'] ?? '';
            if (!isset($plants[$key])) {
                continue;
            }

            $plant = $plants[$key];
            
            // Extract device settings dari AI
            $deviceSettings = $rec['device_settings'] ?? [];
            $actionPlan = $rec['action_plan'] ?? [];
            
            // Build device recommendations
            $deviceRecommendations = [];
            if ($deviceSettings['filtration_needed'] ?? false) {
                $deviceRecommendations[] = "✓ Aktifkan Filtrasi Air";
            }
            if ($deviceSettings['heater_needed'] ?? false) {
                $deviceRecommendations[] = "✓ Aktifkan Pemanas Nikrom (suhu target: " . ($deviceSettings['recommended_temp'] ?? 'auto') . "°C)";
            }
            if ($deviceSettings['salt_adjuster_needed'] ?? false) {
                $deviceRecommendations[] = "✓ Aktifkan Penambah Garam (Target pH: " . ($deviceSettings['recommended_ph'] ?? 'auto') . ")";
            }
            
            $formatted[] = [
                'key' => $key,
                'name' => $plant['name'],
                'description' => $plant['description'],
                'advantages' => $plant['advantages'],
                'ideal_ph' => $plant['ideal_ph_min'] . ' - ' . $plant['ideal_ph_max'],
                'ideal_environment' => $plant['ideal_environment'],
                'badge' => $plant['badge'],
                'water_need' => $plant['water_need'],
                'score' => (int) ($rec['score'] ?? 50),
                'reasons' => [$rec['reasoning'] ?? 'Rekomendasi dari AI berdasarkan analisis data sensor.'],
                'device_settings' => $deviceRecommendations,
                'immediate_actions' => $actionPlan['immediate_actions'] ?? [],
                'device_activation' => $actionPlan['device_activation'] ?? '',
                'monitoring_notes' => $actionPlan['monitoring_notes'] ?? '',
            ];
        }

        $best = $formatted[0] ?? null;
        $actionPlan = $aiResult['action_plan'] ?? [];

        return [
            'best' => $best,
            'items' => $formatted,
            'water_state' => 'aman',
            'sheet' => null,
            'powered_by' => 'AI',
            'water_status_comment' => $aiResult['water_status_comment'] ?? '',
            'action_plan' => $actionPlan,
        ];
    }

    private function fallbackRecommendation($sensor, $spreadsheetRecommendation, $plants): array
    {
        // Jika AI gagal, fallback ke format rekomendasi yang sama dengan hasil AI.
        $formatted = [];
        foreach ($plants as $key => $plant) {
            $formatted[] = [
                'key' => $key,
                'name' => $plant['name'] ?? $key,
                'description' => $plant['description'] ?? '',
                'advantages' => $plant['advantages'] ?? [],
                'ideal_ph' => isset($plant['ideal_ph_min'], $plant['ideal_ph_max'])
                    ? $plant['ideal_ph_min'] . ' - ' . $plant['ideal_ph_max']
                    : '-',
                'ideal_environment' => $plant['ideal_environment'] ?? '',
                'badge' => $plant['badge'] ?? '',
                'water_need' => $plant['water_need'] ?? '',
                'score' => 50,
                'reasons' => ['Rekomendasi fallback karena AI tidak tersedia.'],
                'device_settings' => [],
                'immediate_actions' => [],
                'device_activation' => '',
                'monitoring_notes' => '',
            ];
        }

        return [
            'best' => $formatted[0] ?? null,
            'items' => $formatted,
            'water_state' => 'aman',
            'sheet' => $spreadsheetRecommendation,
            'powered_by' => 'Static',
            'water_status_comment' => '',
            'action_plan' => [],
        ];
    }
}
