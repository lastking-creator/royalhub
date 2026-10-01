<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount(['attendances as attending_count' => function ($q) {
            $q->where('rsvp_status', 'attending');
        }, 'attendances as checked_in_count' => function ($q) {
            $q->where('checked_in', true);
        }])->orderBy('start_time', 'desc')->get();

        return view('admin.events.index', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:event,training,distribution',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'capacity' => 'nullable|integer|min:1',
        ]);

        Event::create($validated);

        return redirect()->back()->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $event->load('attendances.user');
        $users = User::all();

        return view('admin.events.show', compact('event', 'users'));
    }

    public function rsvp(Request $request, Event $event)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'rsvp_status' => 'required|in:attending,declined,maybe',
        ]);

        EventAttendance::updateOrCreate(
            ['event_id' => $event->id, 'user_id' => $request->user_id],
            ['rsvp_status' => $request->rsvp_status]
        );

        return redirect()->back()->with('success', 'RSVP updated.');
    }

    public function checkIn(Event $event, User $user)
    {
        EventAttendance::updateOrCreate(
            ['event_id' => $event->id, 'user_id' => $user->id],
            [
                'checked_in' => true,
                'checked_in_at' => now(),
            ]
        );

        return redirect()->back()->with('success', 'Member checked in.');
    }

    public function report(Event $event)
    {
        $attendances = $event->attendances()->with('user')->get();

        return view('pdf.event-attendance-report', compact('event', 'attendances'));
    }
}