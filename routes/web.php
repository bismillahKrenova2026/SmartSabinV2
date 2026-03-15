<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\PlantRecommendationController;

Route::get('/', [PlantRecommendationController::class, 'index']);
Route::get('/sensor', [SensorController::class, 'getData']);
Route::get('/fetch-blynk', [SensorController::class, 'fetchFromBlynk']);
Route::get('/rekomendasi', [PlantRecommendationController::class, 'index']);
