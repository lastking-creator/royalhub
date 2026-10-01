<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Communications Hub') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Send Message / Broadcast Card -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Send Broadcast or Announcement</h3>
                
                @if(session('success'))
                    <div class="mb-4 p-4 bg-emerald-100 text-emerald-800 rounded-md text-sm font-semibold">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.communications.send') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Communication Type</label>
                            <select name="type" required class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                                <option value="email">Email Newsletter / Notice</option>
                                <option value="sms">SMS Broadcast</option>
                                <option value="announcement">System Announcement</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Target Recipients</label>
                            <select name="recipient_group" required class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                                <option value="approved_members">Approved Members ({{ $approvedMembers }})</option>
                                <option value="all_members">All Registered Users ({{ $totalMembers }})</option>
                                <option value="beneficiaries">Beneficiaries List</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Subject (Optional for SMS)</label>
                            <input type="text" name="subject" class="mt-1 block w-full rounded-md border-gray-300 text-sm" placeholder="e.g. Monthly Meeting Notice">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Message Content</label>
                        <textarea name="message" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 text-sm" placeholder="Type your broadcast message here..."></textarea>
                    </div>

                    <x-primary-button>Send Message</x-primary-button>
                </form>
            </div>

            <!-- Message Logs Table -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Communication Audit Logs</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50 text-xs text-gray-500 uppercase">
                            <th class="p-3">Date & Time</th>
                            <th class="p-3">Type</th>
                            <th class="p-3">Recipient Group</th>
                            <th class="p-3">Subject</th>
                            <th class="p-3">Sender</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr class="border-b text-sm">
                                <td class="p-3 text-gray-600">{{ $log->created_at->setTimezone('Africa/Nairobi')->format('Y-m-d h:i A') }}</td>
                                <td class="p-3"><span class="uppercase px-2 py-1 bg-gray-100 font-bold text-xs rounded">{{ $log->type }}</span></td>
                                <td class="p-3 font-semibold">{{ ucfirst(str_replace('_', ' ', $log->recipient_group)) }}</td>
                                <td class="p-3">{{ $log->subject }}</td>
                                <td class="p-3">{{ $log->sender ? $log->sender->name : 'System' }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded text-xs font-bold bg-emerald-100 text-emerald-800">
                                        {{ ucfirst($log->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">No communication logs recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>