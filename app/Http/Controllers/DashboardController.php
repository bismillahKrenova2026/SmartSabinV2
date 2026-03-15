<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
class DashboardController extends Controller
{
    public function index()
    {
        $history = SensorData::latest()->take(20)->get();

        return view('welcome', compact('history'));
    }
}