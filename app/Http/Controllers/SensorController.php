<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use App\Services\AIService;
use App\Services\BlynkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function fetchFromBlynk(Request $request, BlynkService $blynkService): JsonResponse|RedirectResponse
    {
        $snapshot = $blynkService->snapshot(true);

        if (! $snapshot) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data Blynk tidak dapat dibaca.',
                ], 422);
            }

            return back()->with('status', 'Data Blynk tidak dapat dibaca saat ini.');
        }

        $fillable = (new SensorData())->getFillable();
        $snapshot = array_intersect_key($snapshot, array_flip($fillable));

        $sensor = SensorData::create($snapshot);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data sensor berhasil disinkronkan.',
                'data' => $sensor,
            ]);
        }

        return back()->with('status', 'Data sensor terbaru berhasil disinkronkan ke database.');
    }

    public function latest(BlynkService $blynkService): JsonResponse
    {
        $latest = SensorData::latest()->first();

        if ($latest) {
            return response()->json($latest);
        }

        $snapshot = $blynkService->snapshot(true);

        if ($snapshot) {
            $fillable = (new SensorData())->getFillable();
            $snapshot = array_intersect_key($snapshot, array_flip($fillable));

            return response()->json($snapshot);
        }

        return response()->json([
            'message' => 'Belum ada data sensor.',
        ], 404);
    }

    public function getData(): JsonResponse
    {
        $history = SensorData::latest()->take(20)->get();

        return response()->json($history);
    }

    public function control(Request $request, BlynkService $blynkService): JsonResponse
    {
        $validated = $request->validate([
            'pin' => ['required', 'string'],
            'value' => ['required'],
        ]);

        $success = false;

        if (method_exists($blynkService, 'setPin')) {
            $success = (bool) $blynkService->setPin($validated['pin'], $validated['value']);
        } elseif (method_exists($blynkService, 'writePin')) {
            $success = (bool) $blynkService->writePin($validated['pin'], $validated['value']);
        }

        if (! $success) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim perintah ke Blynk.',
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Perintah berhasil dikirim ke Blynk.',
        ]);
    }

    public function aiAnalysis(AIService $aiService): JsonResponse
    {
        $sensor = SensorData::latest()->first();

        if (! $sensor) {
            return response()->json([
                'result' => 'Belum ada data sensor.',
            ], 404);
        }

        try {
            $result = $aiService->analyze($sensor->toArray());
        } catch (\Throwable $e) {
            return response()->json([
                'result' => 'Gagal mengambil analisis AI.',
            ], 500);
        }

        return response()->json([
            'result' => $result,
        ]);
    }
}