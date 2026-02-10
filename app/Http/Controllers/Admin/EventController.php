<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Str;

class EventController extends Controller
{
    // List all events
    public function index()
    {
        $events = Event::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    // Show the form to create a new event
    public function create()
    {
        return view('admin.events.create');
    }

    // Store the new event
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string',
            'description' => 'required',
        ]);

        $event = new Event();
        $event->title = $request->title;
        $event->slug = Str::slug($request->title);
        $event->date = $request->date; // Ensure your DB has a 'date' column, or change to 'start_date'
        $event->location = $request->location;
        $event->description = $request->description;
        $event->status = 1; // Active by default
        $event->save();

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully!');
    }
    // Show Edit Form
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    // Update Event in Database
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string',
            'description' => 'required',
        ]);

        $event = Event::findOrFail($id);
        $event->title = $request->title;
        $event->slug = Str::slug($request->title);
        $event->date = $request->date;
        $event->location = $request->location;
        $event->description = $request->description;
        $event->status = $request->status; // Active (1) or Draft (0)
        $event->save();

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully!');
    }

    // Delete Event
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully!');
    }
}