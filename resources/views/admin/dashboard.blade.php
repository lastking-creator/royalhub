<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Financial & Membership Overview Cards (3 per row) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Card 1: Total Registered Users -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Users</div>
        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalMembersCount }}</div>
    </div>

    <!-- Card 2: Approved Members -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Approved Members</div>
        <div class="mt-2 text-3xl font-bold text-blue-600">{{ $approvedMembersCount }}</div>
    </div>

    <!-- Card 3: Pending Approval Requests -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pending Requests</div>
        <div class="mt-2 text-3xl font-bold text-yellow-600">{{ $pendingMembers->count() }}</div>
    </div>

    <!-- Card 4: Total Donations Received -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Donations</div>
        <div class="mt-2 text-3xl font-bold text-green-600">
            KSh {{ number_format($totalDonations, 2) }}
        </div>
    </div>

    <!-- Card 5: Total Funds Spent -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Funds Spent</div>
        <div class="mt-2 text-3xl font-bold text-red-600">
            KSh {{ number_format($totalExpenses, 2) }}
        </div>
    </div>

    <!-- Card 6: Net Balance Available -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Net Balance</div>
        <div class="mt-2 text-3xl font-bold {{ $remainingBalance < 0 ? 'text-red-600' : 'text-indigo-600' }}">
            KSh {{ number_format($remainingBalance, 2) }}
        </div>
    </div>
</div>

           <!-- Pending Registrations Component -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        Pending Registration Requests ({{ $pendingMembers->count() }})
                    </h3>
                    <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold">
                        Total Approved: {{ $approvedMembersCount }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applicant Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email & Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registered Date</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($pendingMembers as $member)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $member->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>{{ $member->email }}</div>
                                        <div class="text-xs text-gray-400">{{ $member->phone_number ?? 'No Phone' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $member->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <form action="{{ route('admin.members.approve', $member->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded shadow-sm transition">
                                                Accept
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.members.decline', $member->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Are you sure you want to decline this applicant?')" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded shadow-sm transition">
                                                Decline
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-400 italic">
                                        No pending registrations to show.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>