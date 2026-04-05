<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use App\Services\BlynkService;
use App\Services\PlantRecommendationService;
use App\Services\SpreadsheetService;
use App\Services\WaterProcessingService;
use Illuminate\View\View;

class MonitoringController extends Controller
{
    public function index(
        BlynkService $blynkService,
        SpreadsheetService $spreadsheetService,
        PlantRecommendationService $plantRecommendationService,
        WaterProcessingService $waterProcessingService
    ): View {
        $liveSensor = $blynkService->snapshot();
        $latestSavedSensor = SensorData::latest()->first();
        $sensor = $liveSensor ?? $latestSavedSensor;
        $spreadsheetRecommendation = $spreadsheetService->latestRecommendation();
        $analysis = $plantRecommendationService->recommend($sensor, $spreadsheetRecommendation);
        $waterStatus = $waterProcessingService->evaluate($sensor);
        $activePlantKey = session('active_plant_key');
        $activePlant = $activePlantKey ? data_get(config('smartfarming.plants'), $activePlantKey) : null;

        return view('monitoring', [
            'site' => config('smartfarming.site'),
            'sensor' => $sensor,
            'latestSavedSensor' => $latestSavedSensor,
            'liveSensor' => $liveSensor,
            'spreadsheetRecommendation' => $spreadsheetRecommendation,
            'analysis' => $analysis,
            'recommendedPlants' => $analysis['items'] ?? [],
            'bestRecommendation' => $analysis['best'] ?? null,
            'waterStatus' => $waterStatus,
            'activePlant' => $activePlant,
            'activePlantKey' => $activePlantKey,
        ]);
    }
}
