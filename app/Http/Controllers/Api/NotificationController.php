<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Fetch all notifications for the logged-in app user
    public function index(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'unread_count' => $user->unreadNotifications->count(),
            'notifications' => $user->notifications()->take(50)->get() // Get latest 50
        ], 200);
    }

    // Mark a specific notification as read
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }
}