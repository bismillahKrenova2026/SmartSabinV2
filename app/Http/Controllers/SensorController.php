<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SensorData;

class SensorController extends Controller
{
    public function fetchFromBlynk()
    {
        $token = config('services.blynk.token');

        $data = [
            'ph_air' => Http::get("https://blynk.cloud/external/api/get?token=dBV9SSPhc8T4teNsuUskm9k7FbYIarWm&V0")->body(),
            'ph_tanah' => Http::get("https://blynk.cloud/external/api/get?token=dBV9SSPhc8T4teNsuUskm9k7FbYIarWm&V1")->body(),
            'kelembaban_tanah' => Http::get("https://blynk.cloud/external/api/get?token=dBV9SSPhc8T4teNsuUskm9k7FbYIarWm&V2")->body(),
            'suhu_udara' => Http::get("https://blynk.cloud/external/api/get?token=dBV9SSPhc8T4teNsuUskm9k7FbYIarWm&V3")->body(),
            'kelembaban_udara' => Http::get("https://blynk.cloud/external/api/get?token=dBV9SSPhc8T4teNsuUskm9k7FbYIarWm&V4")->body(),
            'intensitas_cahaya' => Http::get("https://blynk.cloud/external/api/get?token=dBV9SSPhc8T4teNsuUskm9k7FbYIarWm&V5")->body(),
            'sensor_hujan' => Http::get("https://blynk.cloud/external/api/get?token=dBV9SSPhc8T4teNsuUskm9k7FbYIarWm&V6")->body(),
            'status_filtrasi' => Http::get("https://blynk.cloud/external/api/get?token=dBV9SSPhc8T4teNsuUskm9k7FbYIarWm&V8")->body(),
            'pemanas_nikrom' => Http::get("https://blynk.cloud/external/api/get?token=dBV9SSPhc8T4teNsuUskm9k7FbYIarWm&V9")->body(),
            'tambah_garam' => Http::get("https://blynk.cloud/external/api/get?token=dBV9SSPhc8T4teNsuUskm9k7FbYIarWm&V10")->body(),

            'target_ph' => Http::get("https://blynk.cloud/external/api/get?token=dBV9SSPhc8T4teNsuUskm9k7FbYIarWm&V11")->body(),
            'status_aliran' => Http::get("https://blynk.cloud/external/api/get?token=dBV9SSPhc8T4teNsuUskm9k7FbYIarWm&V12")->body(),
            'servo_valve' => Http::get("https://blynk.cloud/external/api/get?token=dBV9SSPhc8T4teNsuUskm9k7FbYIarWm&V13")->body(),
            'target_ph_tanaman' => Http::get("https://blynk.cloud/external/api/get?token=dBV9SSPhc8T4teNsuUskm9k7FbYIarWm&V14")->body(),

            'ph_stlh_air' => Http::get("https://blynk.cloud/external/api/get?token=dBV9SSPhc8T4teNsuUskm9k7FbYIarWm&V15")->body(),

            'suhu_tanah' => Http::get("https://blynk.cloud/external/api/get?token=dBV9SSPhc8T4teNsuUskm9k7FbYIarWm&V17")->body(),
            'flow_rate' => Http::get("https://blynk.cloud/external/api/get?token=dBV9SSPhc8T4teNsuUskm9k7FbYIarWm&V18")->body(),
        ];

        $sensor = SensorData::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $sensor
        ]);
    }

    public function getData()
    {
        $history = SensorData::latest()->take(20)->get();
        return response()->json($history);
    }
}
