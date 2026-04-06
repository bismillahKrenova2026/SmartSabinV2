<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use App\Services\BlynkService;
use App\Services\SpreadsheetService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(BlynkService $blynkService, SpreadsheetService $spreadsheetService): View
    {
        $liveSensor = $blynkService->snapshot(true);
        $latestSavedSensor = SensorData::latest()->first();
        $latest = $liveSensor ? (object) $liveSensor : $latestSavedSensor;
        $latestRecommendation = $spreadsheetService->latestRecommendation();

        return view('welcome', [
            'site' => config('smartfarming.site'),
            'plants' => config('smartfarming.plants', []),
            'latest' => $latest,
            'latestRecommendation' => $latestRecommendation,
        ]);
    }
}
