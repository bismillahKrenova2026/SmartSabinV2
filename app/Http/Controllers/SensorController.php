<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        $data = SensorData::create($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getData()
    {
        $history = SensorData::latest()->take(20)->get();

        return response()->json($history);
    }
}