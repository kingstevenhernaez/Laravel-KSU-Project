<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DocumentRequest;
use Illuminate\Support\Str;

class DocumentRequestController extends Controller
{
    // List all requests made by the logged-in alumni
    public function index()
    {
        $requests = DocumentRequest::where('user_id', Auth::id())
                                   ->orderBy('created_at', 'desc')
                                   ->get();
                                   
        return view('alumni.documents.index', compact('requests'));
    }

    // Show the request form
    public function create()
    {
        return view('alumni.documents.create');
    }

    // Save the new request to the database
    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string',
            'copies'        => 'required|integer|min:1|max:10',
            'purpose'       => 'required|string|max:500'
        ]);

        // Generate a random tracking code like REQ-202603-A8F9X
        $trackingCode = 'REQ-' . date('Ym') . '-' . strtoupper(Str::random(5));

        DocumentRequest::create([
            'user_id'       => Auth::id(),
            'tracking_code' => $trackingCode,
            'document_type' => $request->document_type,
            'copies'        => $request->copies,
            'purpose'       => $request->purpose,
            'status'        => 'Pending'
        ]);

        return redirect()->route('alumni.documents.index')->with('success', 'Document request submitted successfully! Your tracking code is ' . $trackingCode);
    }
}