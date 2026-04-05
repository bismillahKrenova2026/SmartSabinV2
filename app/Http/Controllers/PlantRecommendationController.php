<?php

namespace App\Http\Controllers;

use App\Services\SpreadsheetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class PlantRecommendationController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('home');
    }

    public function latestRecommendation(SpreadsheetService $spreadsheetService): JsonResponse
    {
        $recommendation = $spreadsheetService->latestRecommendation();

        if (! $recommendation) {
            return response()->json([
                'rekomendasi_tanaman' => '-',
                'status_servo' => '-',
                'target_ph' => '-',
                'catatan' => null,
            ], 404);
        }

        return response()->json($recommendation);
    }
}
