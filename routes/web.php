<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\SensorController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::post('/ai-analysis', [SensorController::class, 'aiAnalysis'])->name('ai.analysis');
    Route::post('/control', [SensorController::class, 'control'])->name('control');
});
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/recommendation', [PlantController::class, 'recommendation'])->name('recommendation');
    Route::get('/rekomendasi', [PlantController::class, 'recommendation']);
    Route::post('/plant/select', [PlantController::class, 'select'])->name('plant.select');
    Route::post('/plant/change', [PlantController::class, 'change'])->name('plant.change');
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring');
    Route::get('/pantauan', [MonitoringController::class, 'index']);
    Route::post('/sensor/sync', [SensorController::class, 'fetchFromBlynk'])->name('sensor.sync');
});
