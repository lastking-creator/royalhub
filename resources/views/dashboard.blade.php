<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Member Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Member Welcome & ID Card Banner -->
            <div class="bg-indigo-700 text-white p-6 rounded-lg shadow-md flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold">Welcome back, {{ $user->name }}!</h3>
                    <p class="text-indigo-200 text-sm">Member ID: #CBO-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }} | Status: <span class="uppercase font-bold text-emerald-300">{{ $user->status ?? 'Approved' }}</span></p>
                </div>
                <div>
                    <span class="bg-white text-indigo-900 font-bold px-4 py-2 rounded-lg text-sm shadow">
                        Total Contributed: KSh {{ number_format($totalContributed, 2) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Upcoming Events & RSVP Section -->
                <div class="md:col-span-2 bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Upcoming Events & Distributions</h3>
                    <div class="space-y-4">
                        @forelse($upcomingEvents as $event)
                            <div class="p-4 border rounded-lg flex justify-between items-center">
                                <div>
                                    <span class="text-xs font-bold uppercase px-2 py-0.5 bg-gray-100 rounded text-gray-600">{{ $event->type }}</span>
                                    <h4 class="font-bold text-gray-800 text-md mt-1">{{ $event->title }}</h4>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($event->start_time)->setTimezone('Africa/Nairobi')->format('M d, Y @ h:i A') }} | {{ $event->location ?? 'Main Office' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs font-semibold px-3 py-1 rounded bg-indigo-50 text-indigo-700">
                                        RSVP: {{ ucfirst($myRsvps[$event->id] ?? 'Not Sent') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No upcoming activities scheduled at the moment.</p>
                        @endforelse
                    </div>
                </div>

                <!-- CBO Announcements Feed -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Latest Notices</h3>
                    <div class="space-y-4">
                        @forelse($announcements as $notice)
                            <div class="border-b pb-3">
                                <span class="text-xs text-gray-400">{{ $notice->created_at->setTimezone('Africa/Nairobi')->format('M d, H:i A') }}</span>
                                <h5 class="font-semibold text-sm text-gray-800">{{ $notice->subject }}</h5>
                                <p class="text-xs text-gray-600 mt-1">{{ Str::limit($notice->message, 80) }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No notices available.</p>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Contribution Ledger -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4">My Financial Records</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50 text-xs text-gray-500 uppercase">
                            <th class="p-3">Date</th>
                            <th class="p-3">Transaction / Purpose</th>
                            <th class="p-3">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myDonations as $donation)
                            <tr class="border-b text-sm">
                                <td class="p-3">{{ $donation->created_at->setTimezone('Africa/Nairobi')->format('Y-m-d') }}</td>
                                <td class="p-3">{{ $donation->purpose ?? 'General Contribution' }}</td>
                                <td class="p-3 font-bold text-emerald-600">KSh {{ number_format($donation->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="p-4 text-center text-gray-500">No contribution records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>