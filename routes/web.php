<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SensorController;

Route::get('/', [DashboardController::class, 'index']);
Route::get('/sensor', [SensorController::class, 'getData']);
