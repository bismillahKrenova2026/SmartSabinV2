<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SensorData;

class PlantRecommendationController extends Controller
{
    public function index()
    {
        // ambil 1 data sensor terbaru
        $latest = SensorData::latest()->first();

        // ambil rekomendasi dari google sheet
        $url = env('GOOGLE_SHEET_WEB_APP_URL');

        $latestRecommendation = null;

        if ($url) {
            $response = Http::withoutVerifying()->get($url);

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data)) {
                    $latestRecommendation = collect($data)->last();
                }
            }
        }

        return view('welcome', compact('latest','latestRecommendation'));
    }

public function latestRecommendation()
{
    $url = env('GOOGLE_SHEET_WEB_APP_URL');

    if (!$url) {
        return response()->json(null, 404);
    }

    $response = Http::withoutVerifying()->get($url);

    if ($response->successful()) {
        $data = $response->json();

        if (!empty($data)) {
            return response()->json(collect($data)->last());
        }
    }

    return response()->json(null, 404);
}
}