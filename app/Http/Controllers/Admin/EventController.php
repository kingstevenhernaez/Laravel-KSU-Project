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
    /**
     * List all events with pagination
     */
    public function index()
    {
        $events = Event::latest()->paginate(10); 
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form to create a new event
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Store the new event in the database
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'location'    => 'required',
            'date'        => 'required', 
        ]);

        // Creating the event with required fields for your migration
        $event = Event::create([
            'title'             => $request->title,
            'slug'              => Str::slug($request->title) . '-' . time(),
            'description'       => $request->description,
            'location'          => $request->location,
            'date'              => $request->date,
            'user_id'           => Auth::id(),
            'event_category_id' => 1, // Default category
            'thumbnail'         => 0, // Default integer value
            'status'            => 1  // Assuming 1 for Active/Pending
        ]);

        // Note: If you have the EventObserver registered, 
        // it will handle the alumni emails automatically.

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully!');
    }

    /**
     * Show the form to edit an existing event
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the event in the database
     */
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
        // Update slug only if title changes, or maintain original
        $event->slug = Str::slug($request->title) . '-' . $event->id;
        $event->date = $request->date;
        $event->location = $request->location;
        $event->description = $request->description;
        
        // Handle status if your form includes it (1 for Active, 0 for Draft)
        if ($request->has('status')) {
            $event->status = $request->status;
        }
        
        $event->save();

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the event from the database
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully!');
    }
}