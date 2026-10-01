<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Events, Trainings & Distributions</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Create Event Card -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Create New Activity</h3>
                <form action="{{ route('admin.events.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" required class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        <select name="type" class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                            <option value="event">Event</option>
                            <option value="training">Training</option>
                            <option value="distribution">Distribution</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" name="location" class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Time</label>
                        <input type="datetime-local" name="start_time" required class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Time</label>
                        <input type="datetime-local" name="end_time" class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Capacity</label>
                        <input type="number" name="capacity" class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                    </div>
                    <div class="md:col-span-3">
                        <x-primary-button>Create Activity</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- List Events -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">All Activities</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50 text-xs text-gray-500 uppercase">
                            <th class="p-3">Title</th>
                            <th class="p-3">Type</th>
                            <th class="p-3">Date</th>
                            <th class="p-3">RSVPs</th>
                            <th class="p-3">Checked In</th>
                            <th class="p-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr class="border-b text-sm">
                                <td class="p-3 font-bold">{{ $event->title }}</td>
                                <td class="p-3"><span class="capitalize px-2 py-1 bg-gray-100 rounded text-xs">{{ $event->type }}</span></td>
                                <td class="p-3">{{ \Carbon\Carbon::parse($event->start_time)->format('Y-m-d H:i') }}</td>
                                <td class="p-3 font-semibold text-indigo-600">{{ $event->attending_count }}</td>
                                <td class="p-3 font-semibold text-emerald-600">{{ $event->checked_in_count }}</td>
                                <td class="p-3">
                                    <a href="{{ route('admin.events.show', $event) }}" class="text-indigo-600 hover:underline font-bold">Manage Attendance & Check-in</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-4 text-center text-gray-500">No activities found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>