<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey; // Change this if your model is named differently
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index()
    {
        // Fetch surveys that are marked as active
        $surveys = Survey::where('is_active', 1)
                         ->orderBy('created_at', 'desc')
                         ->get();

        return response()->json([
            'success' => true,
            'surveys' => $surveys
        ]);
    }
}