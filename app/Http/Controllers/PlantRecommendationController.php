<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Sheets;

class PlantRecommendationController extends Controller
{
    public function index()
    {
        $client = new Client();
        // Path ke file JSON yang disimpan di storage
        $client->setAuthConfig(storage_path('app/google-auth.json'));
        $client->addScope(Sheets::SPREADSHEETS_READONLY);

        $service = new Sheets($client);
        $spreadsheetId = env('GOOGLE_SPREADSHEET_ID');
        
        // Tentukan nama sheet dan rentang data (misal: Sheet1 baris A ke E)
        $range = 'Sheet1!A2:E'; 

        try {
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();

            if (empty($values)) {
                $recommendations = [];
            } else {
                $recommendations = $values;
            }

            return view('welcome.index', compact('recommendations'));
        } catch (\Exception $e) {
            return view('welcome', ['recommendations' => []])->withErrors($e->getMessage());
    }
}
}