<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Members Management') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'list' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Navigation Tabs -->
            <div class="mb-6 border-b border-gray-200 bg-white rounded-t-lg px-4 pt-4 shadow-sm">
                <nav class="-mb-px flex space-x-8">
                    <button @click="activeTab = 'list'" :class="activeTab === 'list' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Registered Members
                    </button>
                    <button @click="activeTab = 'import'" :class="activeTab === 'import' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Import Members
                    </button>
                </nav>
            </div>

            <!-- Tab 1: Member List -->
            <div x-show="activeTab === 'list'">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">CBO Members Registry</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($members as $member)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $member->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $member->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ ucfirst($member->role ?? 'Member') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                            @if($member->status === 'approved')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                            @elseif($member->status === 'declined')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Declined
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $member->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            @if($member->status === 'approved')
                                                <a href="{{ route('admin.members.print-form', $member->id) }}" 
                                                   target="_blank" 
                                                   class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded shadow-sm transition">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                    Download Form
                                                </a>
                                            @elseif($member->status === 'pending')
                                                <form action="{{ route('admin.members.approve', $member->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.members.decline', $member->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded transition">
                                                        Decline
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400 italic">No Actions</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No registered members found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    @if(method_exists($members, 'links'))
                        <div class="mt-4">
                            {{ $members->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tab 2: CSV Import -->
            <div x-show="activeTab === 'import'" x-cloak>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Bulk Import Members</h3>
                    <form action="{{ route('admin.members.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="csv_file" :value="__('Upload CSV File')" />
                            <input id="csv_file" name="csv_file" type="file" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".csv" required />
                        </div>
                        <div class="flex justify-end">
                            <x-primary-button>{{ __('Upload & Import') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>