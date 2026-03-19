<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentRequest;
use Illuminate\Support\Str;

class DocumentRequestController extends Controller
{
    // 🟢 Fetch all document requests for the logged-in mobile user
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $requests = DocumentRequest::where('user_id', $userId)
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        // Notice we wrap it in a 'requests' object so Flutter can easily find it!
        return response()->json([
            'requests' => $requests
        ], 200);
    }

    // 🟢 Save a new document request coming from the mobile app
    public function store(Request $request)
    {
        // 1. Validate the incoming data from Flutter
        $request->validate([
            'document_type' => 'required|string',
            'copies'        => 'required|integer|min:1|max:10',
            'purpose'       => 'required|string|max:500'
        ]);

        // 2. Generate a secure, unique tracking code
        $trackingCode = 'REQ-' . date('Ym') . '-' . strtoupper(Str::random(5));

        // 3. Save it to the database
        $documentRequest = DocumentRequest::create([
            'user_id'       => $request->user()->id,
            'tracking_code' => $trackingCode,
            'document_type' => $request->document_type,
            'copies'        => $request->copies,
            'purpose'       => $request->purpose,
            'status'        => 'Pending'
        ]);

        // 4. Tell Flutter it was a success
        return response()->json([
            'success' => true,
            'message' => 'Document requested successfully!',
            'tracking_code' => $trackingCode
        ], 201);
    }
}