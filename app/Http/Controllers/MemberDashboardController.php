<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\Donation;
use App\Models\CommunicationLog;
use Illuminate\Http\Request;

class MemberDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Upcoming Events & Member RSVPs
        $upcomingEvents = Event::where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get();

        $myRsvps = EventAttendance::where('user_id', $user->id)
            ->pluck('rsvp_status', 'event_id')
            ->toArray();

        // 2. Personal Donation History
        $myDonations = Donation::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
            
        $totalContributed = Donation::where('user_id', $user->id)->sum('amount');

        // 3. Announcements Feed
        $announcements = CommunicationLog::whereIn('recipient_group', ['all_members', 'approved_members'])
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard', compact('user', 'upcomingEvents', 'myRsvps', 'myDonations', 'totalContributed', 'announcements'));
    }
}