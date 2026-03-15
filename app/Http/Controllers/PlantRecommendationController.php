<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Sheets;
use Illuminate\Support\Facades\Http; 
use App\Models\SensorData; // Tambahkan ini untuk mengambil data DB

class PlantRecommendationController extends Controller
{
    public function index()
    {
        // 1. Ambil data dari Database (untuk pH Air, dsb)
        $history = SensorData::latest()->take(20)->get();

        // 2. Ambil data dari Google Sheets (untuk Rekomendasi)
        $url = env('GOOGLE_SHEET_WEB_APP_URL');
        $response = Http::withoutVerifying()->get($url);

        $latestRecommendation = null;
        if ($response->successful()) {
            $data = $response->json();
            if (!empty($data)) {
                $latestRecommendation = collect($data)->last();
            }
        }

        // 3. Kirim KEDUA variabel ke view welcome
        return view('welcome', compact('history', 'latestRecommendation'));
    }
}