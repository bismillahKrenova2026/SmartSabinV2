<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use App\Services\AIService;
use App\Services\BlynkService;
use App\Services\SpreadsheetService;
use App\Services\WaterProcessingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PlantController extends Controller
{
    public function recommendation(
        BlynkService $blynkService,
        SpreadsheetService $spreadsheetService,
        AIService $aiService,
        WaterProcessingService $waterProcessingService
    ): View {
        $liveSensor = $blynkService->snapshot(true);
        $latestSavedSensor = SensorData::latest()->first();
        $sensor = $liveSensor ?? $latestSavedSensor;
        $spreadsheetRecommendation = $spreadsheetService->latestRecommendation();
        
        // Gunakan AI untuk rekomendasi
        // Convert sensor to array - handle both object dan array
        $sensorArray = is_array($sensor) ? $sensor : ($sensor ? $sensor->toArray() : []);
        $analysis = $aiService->recommendPlants($sensorArray, $spreadsheetRecommendation);
        
        $waterStatus = $waterProcessingService->evaluate($sensor);
        $activePlantKey = session('active_plant_key');

        return view('recommendation', [
            'site' => config('smartfarming.site'),
            'sensor' => $sensor,
            'spreadsheetRecommendation' => $spreadsheetRecommendation,
            'analysis' => $analysis,
            'recommendedPlants' => $analysis['items'] ?? [],
            'bestRecommendation' => $analysis['best'] ?? null,
            'waterStatus' => $waterStatus,
            'activePlantKey' => $activePlantKey,
            'plants' => config('smartfarming.plants', []),
        ]);
    }

    public function select(Request $request): RedirectResponse
    {
        $validKeys = array_keys(config('smartfarming.plants', []));

        $validated = $request->validate([
            'plant_key' => ['required', 'string', Rule::in($validKeys)],
        ]);

        $plant = data_get(config('smartfarming.plants'), $validated['plant_key']);

        Session::put('active_plant_key', $validated['plant_key']);
        Session::put('active_plant_name', $plant['name'] ?? $validated['plant_key']);
        Session::put('active_plant_selected_at', now()->toDateTimeString());

        return redirect()->route('monitoring')->with('status', 'Tanaman aktif berhasil dipilih. Monitoring sekarang fokus pada tanaman tersebut.');
    }

    public function change(Request $request): RedirectResponse
    {
        Session::forget(['active_plant_key', 'active_plant_name', 'active_plant_selected_at']);

        return redirect()->route('recommendation')->with('status', 'Pilihan tanaman sudah direset. Silakan pilih tanaman yang baru.');
    }
}
