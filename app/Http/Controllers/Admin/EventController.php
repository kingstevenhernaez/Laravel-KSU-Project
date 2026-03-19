<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Mail\EventNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10); 
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'location'    => 'required',
            'date'        => 'required', 
        ]);

        $event = Event::create([
            'title'             => $request->title,
            'slug'              => Str::slug($request->title) . '-' . time(),
            'description'       => $request->description,
            'location'          => $request->location,
            'date'              => $request->date,
            'user_id'           => Auth::id(),
            'event_category_id' => 1, 
            'thumbnail'         => 0, 
            'status'            => 1  
        ]);

        // [NEW] Broadcast Notification to all Alumni
        $alumni = \App\Models\User::where('role', 2)->where('status', 1)->get();
        $title = "Upcoming University Event";
        $message = "Mark your calendar for: " . $request->title;
        
        \Illuminate\Support\Facades\Notification::send($alumni, new \App\Notifications\AppNotification($title, $message, 'event'));

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully!');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'date'        => 'required',
            'location'    => 'required|string',
            'description' => 'required',
        ]);

        $event = Event::findOrFail($id);
        
        $event->title = $request->title;
        $event->slug = Str::slug($request->title) . '-' . $event->id;
        $event->date = $request->date;
        $event->location = $request->location;
        $event->description = $request->description;
        
        if ($request->has('status')) {
            $event->status = $request->status;
        }
        
        $event->save();

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully!');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully!');
    }
}