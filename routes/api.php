<?php

use App\Http\Controllers\PlantRecommendationController;
use App\Http\Controllers\SensorController;
use Illuminate\Support\Facades\Route;

Route::get('/sensor/latest', [SensorController::class, 'latest']);
Route::get('/sensor/history', [SensorController::class, 'getData']);
Route::get('/recommendation/latest', [PlantRecommendationController::class, 'latestRecommendation']);
Route::post('/sensor/fetch-blynk', [SensorController::class, 'fetchFromBlynk']);
