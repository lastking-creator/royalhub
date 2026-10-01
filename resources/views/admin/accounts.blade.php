<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-100 text-emerald-800 rounded-md text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-md text-sm font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">All Registered User Accounts</h3>
                    <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-3 py-1 rounded-full">
                        Total Accounts: {{ $users->count() }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" style="width: 100%; border: 1px solid #e5e7eb;">
                        <thead>
                            <tr style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                                <th style="padding: 12px 16px; font-size: 12px; font-weight: 600; text-transform: uppercase; color: #374151;">Name</th>
                                <th style="padding: 12px 16px; font-size: 12px; font-weight: 600; text-transform: uppercase; color: #374151;">Email</th>
                                <th style="padding: 12px 16px; font-size: 12px; font-weight: 600; text-transform: uppercase; color: #374151;">Password Hash</th>
                                <th style="padding: 12px 16px; font-size: 12px; font-weight: 600; text-transform: uppercase; color: #374151;">Role</th>
                                <th style="padding: 12px 16px; font-size: 12px; font-weight: 600; text-transform: uppercase; color: #374151;">Actions</th>
                            </tr>
                        </thead>
                        <tbody style="background-color: #ffffff;">
                            @forelse ($users as $user)
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 12px 16px; font-size: 14px; font-weight: 600; color: #111827;">{{ $user->name }}</td>
                                    <td style="padding: 12px 16px; font-size: 14px; color: #4b5563;">{{ $user->email }}</td>
                                    <td style="padding: 12px 16px; font-size: 11px; color: #9ca3af; font-family: monospace;" title="Encrypted Password">
                                        {{ Str::limit($user->password, 15) }}
                                    </td>
                                    <td style="padding: 12px 16px; font-size: 14px;">
                                        @if($user->role === 'admin')
                                            <span style="background-color: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 700;">Admin</span>
                                        @else
                                            <span style="background-color: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 700;">Member</span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px 16px; font-size: 14px;">
                                        <div style="display: flex; gap: 8px; align-items: center;">
                                            <a href="{{ route('admin.accounts.edit', $user->id) }}" style="background-color: #2563eb; color: #ffffff; padding: 4px 10px; font-size: 12px; font-weight: 600; border-radius: 4px; text-decoration: none;">
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('admin.accounts.delete', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete this account?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="background-color: #dc2626; color: #ffffff; padding: 4px 10px; font-size: 12px; font-weight: 600; border-radius: 4px; border: none; cursor: pointer;">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="padding: 16px; text-align: center; color: #6b7280;">No user accounts found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>