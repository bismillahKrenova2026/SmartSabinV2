<?php
                      
use App\Http\Controllers\PlantRecommendationController;

Route::get('/', [PlantRecommendationController::class, 'index']);
Route::get('/rekomendasi', [PlantRecommendationController::class, 'index']);