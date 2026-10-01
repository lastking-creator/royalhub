<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $event->title }} ({{ ucfirst($event->type) }})</h2>
            <a href="{{ route('admin.events.report', $event) }}" target="_blank" class="px-4 py-2 bg-indigo-600 text-white rounded text-xs font-bold uppercase">Print Attendance Report</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- RSVP Control -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Record Member RSVP</h3>
                <form action="{{ route('admin.events.rsvp', $event) }}" method="POST" class="flex gap-4 items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Select Member</label>
                        <select name="user_id" class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="rsvp_status" class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                            <option value="attending">Attending</option>
                            <option value="declined">Declined</option>
                            <option value="maybe">Maybe</option>
                        </select>
                    </div>
                    <x-primary-button>Update RSVP</x-primary-button>
                </form>
            </div>

            <!-- Roster & Check-In Table -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Attendee Roster & Live Check-In</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50 text-xs text-gray-500 uppercase">
                            <th class="p-3">Member</th>
                            <th class="p-3">RSVP Status</th>
                            <th class="p-3">Check-In Status</th>
                            <th class="p-3">Check-In Time</th>
                            <th class="p-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($event->attendances as $att)
                            <tr class="border-b text-sm">
                                <td class="p-3 font-semibold">{{ $att->user->name }}</td>
                                <td class="p-3"><span class="capitalize px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">{{ $att->rsvp_status }}</span></td>
                                <td class="p-3">
                                    @if($att->checked_in)
                                        <span class="px-2 py-1 rounded text-xs bg-emerald-100 text-emerald-800 font-bold">Checked In</span>
                                    @else
                                        <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                </td>
                                <td class="p-3">{{ $att->checked_in_at ? \Carbon\Carbon::parse($att->checked_in_at)->setTimezone('Africa/Nairobi')->format('h:i:s A') : '-' }}</td>
                                <td class="p-3">
                                    @if(!$att->checked_in)
                                        <form action="{{ route('admin.events.checkin', [$event, $att->user]) }}" method="POST">
                                            @csrf
                                            <button class="px-3 py-1 bg-emerald-600 text-white rounded text-xs font-bold">Check In</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-4 text-center text-gray-500">No members registered for this event yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>