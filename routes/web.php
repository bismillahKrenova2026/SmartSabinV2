<?php
                      
use App\Http\Controllers\PlantRecommendationController;
use App\Http\Controllers\SensorController;

Route::get('/', [PlantRecommendationController::class, 'index']); // dashboard
Route::get('/rekomendasi', [PlantRecommendationController::class, 'index']); // juga bisa pakai halaman rekomendasi

Route::get('/sensor', [SensorController::class, 'getData']);
Route::get('/fetch-blynk', [SensorController::class, 'fetchFromBlynk']);
Route::get('/api/sensor/latest', [PlantRecommendationController::class, 'latestSensorData']);