<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Documents & Compliance Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Success Alert -->
            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg border border-green-200 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Validation Errors Alert -->
            @if ($errors->any())
                <div class="p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">
                    <strong class="font-bold">Please fix the following upload errors:</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Expiry Warning Alerts Banner -->
            @if($expired->count() > 0 || $expiringSoon->count() > 0)
                <div class="space-y-3">
                    @foreach($expired as $doc)
                        <div class="p-4 bg-red-100 border border-red-200 text-red-800 rounded-lg flex justify-between items-center">
                            <div>
                                <strong>EXPIRED LICENSE / CERTIFICATE:</strong> {{ $doc->title }} expired on {{ $doc->expires_at ? \Carbon\Carbon::parse($doc->expires_at)->format('M d, Y') : 'N/A' }}.
                            </div>
                            <span class="text-xs font-bold uppercase bg-red-200 px-2 py-1 rounded text-red-900">Action Required</span>
                        </div>
                    @endforeach

                    @foreach($expiringSoon as $doc)
                        <div class="p-4 bg-yellow-100 border border-yellow-200 text-yellow-800 rounded-lg flex justify-between items-center">
                            <div>
                                <strong>EXPIRING SOON:</strong> {{ $doc->title }} will expire on {{ $doc->expires_at ? \Carbon\Carbon::parse($doc->expires_at)->format('M d, Y') : 'N/A' }} (In {{ \Carbon\Carbon::parse($doc->expires_at)->diffInDays(now()) }} days).
                            </div>
                            <span class="text-xs font-bold uppercase bg-yellow-200 px-2 py-1 rounded text-yellow-900">Renewal Warning</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Document Upload Form -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4">Upload Official Document</h3>
                <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <x-input-label value="Document Title" />
                        <input type="text" name="title" class="w-full border-gray-300 rounded-md" required placeholder="e.g. KRA Tax Exemption Cert" value="{{ old('title') }}" />
                    </div>

                    <div>
                        <x-input-label value="Document Category" />
                        <select name="category" class="w-full border-gray-300 rounded-md" required>
                            <option value="registration_cert" {{ old('category') == 'registration_cert' ? 'selected' : '' }}>Registration Certificate</option>
                            <option value="tax_status" {{ old('category') == 'tax_status' ? 'selected' : '' }}>Tax Status / Exemption</option>
                            <option value="policy" {{ old('category') == 'policy' ? 'selected' : '' }}>Internal Policy</option>
                            <option value="license" {{ old('category') == 'license' ? 'selected' : '' }}>Operating License</option>
                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label value="Version Number" />
                        <input type="number" name="version" class="w-full border-gray-300 rounded-md" min="1" value="{{ old('version', 1) }}" required />
                    </div>

                    <div>
                        <x-input-label value="Expiration Date (If Applicable)" />
                        <input type="date" name="expires_at" class="w-full border-gray-300 rounded-md" value="{{ old('expires_at') }}" />
                    </div>

                    <div>
                        <x-input-label value="File Upload (PDF/Doc)" />
                        <input type="file" name="document" class="w-full border-gray-300 rounded-md" required />
                    </div>

                    <div class="flex items-end">
                        <x-primary-button class="w-full justify-center">Upload Compliance Document</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Documents Table -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4">Compliance Document Repository</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50 text-xs text-gray-500 uppercase">
                                <th class="p-3">Title</th>
                                <th class="p-3">Category</th>
                                <th class="p-3">Version</th>
                                <th class="p-3">Expiry Status</th>
                                <th class="p-3">Uploaded By</th>
                                <th class="p-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y text-sm">
                            @forelse($documents as $doc)
                                <tr>
                                    <td class="p-3 font-semibold text-gray-900">{{ $doc->title }}</td>
                                    <td class="p-3 uppercase text-xs font-medium text-gray-600">{{ str_replace('_', ' ', $doc->category) }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 bg-gray-100 border text-xs font-bold rounded">v{{ $doc->version }}.0</span>
                                    </td>
                                    <td class="p-3">
                                        @if(!$doc->expires_at)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded">No Expiration</span>
                                        @elseif(\Carbon\Carbon::parse($doc->expires_at)->isPast())
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-bold rounded">
                                                Expired ({{ \Carbon\Carbon::parse($doc->expires_at)->format('Y-m-d') }})
                                            </span>
                                        @elseif(\Carbon\Carbon::parse($doc->expires_at)->diffInDays(now()) <= 30)
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded">
                                                Expires {{ \Carbon\Carbon::parse($doc->expires_at)->format('Y-m-d') }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded">
                                                Valid till {{ \Carbon\Carbon::parse($doc->expires_at)->format('Y-m-d') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-gray-500">{{ $doc->uploader->name ?? 'System' }}</td>
                                    <td class="p-3">
                                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-bold text-xs">
                                            Download
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-gray-400 italic">No compliance documents uploaded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>