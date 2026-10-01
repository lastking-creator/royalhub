<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h3 class="text-lg font-bold text-gray-800 mb-6">Edit Welcome Page Content</h3>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-emerald-100 text-emerald-800 rounded-md text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.welcome.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Main Title</label>
                        <input type="text" name="title" value="{{ old('title', $content->title) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Subtitle / Tagline</label>
                        <input type="text" name="subtitle" value="{{ old('subtitle', $content->subtitle) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">About / Body Content</label>
                        <textarea name="body_content" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" required>{{ old('body_content', $content->body_content) }}</textarea>
                    </div>

                    <!-- Mission & Vision Inputs -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Our Mission</label>
                            <textarea name="mission" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Enter CBO Mission statement...">{{ old('mission', $content->mission) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Our Vision</label>
                            <textarea name="vision" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Enter CBO Vision statement...">{{ old('vision', $content->vision) }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" style="background-color: #047857; color: #ffffff; padding: 10px 20px; font-size: 14px; font-weight: 600; border-radius: 6px; border: none; cursor: pointer;">
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>