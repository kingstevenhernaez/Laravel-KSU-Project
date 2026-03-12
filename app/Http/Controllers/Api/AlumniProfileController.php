<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlumniProfileController extends Controller
{
    /**
     * Get the logged-in alumnus's profile for the mobile app.
     */
    public function show(Request $request)
    {
        $alumnus = $request->user();

        // 🟢 Generate a public, full URL for the optimized photo.
        $photo_url = null;
        if ($alumnus->photo_path) {
            // Convert the stored path into a clean, public URL
            $path = str_replace('public/', '', $alumnus->photo_path);
            $photo_url = Storage::disk('public')->url($path);
        }

        return response()->json([
            'success' => true,
            'alumnus' => [
                'name'      => $alumnus->name,
                'email'     => $alumnus->email,
                'course'    => $alumnus->course ?? 'BSIT', // Pull from database!
                'photo_url' => $photo_url, // 🟢 This is the URL Flutter will use
            ]
        ]);
    }
}