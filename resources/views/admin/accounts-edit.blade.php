<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h3 class="text-lg font-bold text-gray-800 mb-6">Edit User Account</h3>

                <form method="POST" action="{{ route('admin.accounts.update', $user->id) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Role</label>
                        <select name="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
                            <option value="member" {{ $user->role === 'member' ? 'selected' : '' }}>Member</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Enter new password">
                    </div>

                    <div class="flex justify-between items-center pt-4">
                        <a href="{{ route('admin.accounts') }}" class="text-sm text-gray-600 hover:underline">Cancel</a>
                        <button type="submit" style="background-color: #047857; color: #ffffff; padding: 8px 18px; font-size: 14px; font-weight: 600; border-radius: 6px; border: none; cursor: pointer;">
                            Update Account
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>