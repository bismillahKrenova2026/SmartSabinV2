<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantRecommendationController;
use App\Http\Controllers\SensorController;

Route::get('/sensor/latest', [PlantRecommendationController::class, 'latestSensorData']);

Route::get('/recommendation/latest', [PlantRecommendationController::class, 'latestRecommendation']);

Route::get('/sensor', [SensorController::class, 'getData']);

Route::get('/fetch-blynk', [SensorController::class, 'fetchFromBlynk']);