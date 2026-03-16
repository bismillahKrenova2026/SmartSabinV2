<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SensorData;

class PlantRecommendationController extends Controller
{
    // Menampilkan halaman dashboard
    public function index()
    {
        // Ambil 1 data sensor terbaru
        $latest = SensorData::orderBy('created_at', 'desc')->first();

        // Ambil rekomendasi dari Google Sheet
        $url = env('GOOGLE_SHEET_WEB_APP_URL');
        $response = Http::withoutVerifying()->get($url);

        $latestRecommendation = null;
        if ($response->successful()) {
            $data = $response->json();
            if (!empty($data)) $latestRecommendation = collect($data)->last();
        }

        return view('welcome', compact('latest', 'latestRecommendation'));
    }

    // API untuk realtime
    public function latestSensorData()
    {
        $latest = SensorData::orderBy('created_at', 'desc')->first();

        if ($latest) {
            return response()->json([
                'ph_air' => $latest->ph_air,
                'ph_tanah' => $latest->ph_tanah,
                'suhu_udara' => $latest->suhu_udara,
                'kelembaban_tanah' => $latest->kelembaban_tanah,
                'created_at' => $latest->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->json(null, 404);
    }
}