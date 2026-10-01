<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Grant & Compliance Management') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ tab: 'tracking' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
            @endif

            <!-- Navigation Tabs -->
            <div class="flex space-x-4 border-b bg-white p-4 rounded-t-lg shadow-sm">
                <button @click="tab = 'tracking'" :class="tab === 'tracking' ? 'text-indigo-600 border-b-2 border-indigo-600 font-bold' : 'text-gray-500'" class="pb-2">Grants & Deliverables</button>
                <button @click="tab = 'documents'" :class="tab === 'documents' ? 'text-indigo-600 border-b-2 border-indigo-600 font-bold' : 'text-gray-500'" class="pb-2">Document Storage & Compliance</button>
            </div>

            <!-- Tab 1: Grant Tracking & Deliverables -->
            <div x-show="tab === 'tracking'" class="bg-white p-6 rounded-b-lg shadow-sm space-y-8">
                <!-- Add New Grant Form -->
                <div>
                    <h3 class="font-bold text-gray-900 mb-4">Register New Grant</h3>
                    <form action="{{ route('admin.grants.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @csrf
                        <div>
                            <x-input-label value="Funder Name" />
                            <input type="text" name="funder_name" class="w-full border-gray-300 rounded-md" required placeholder="e.g. USAID / Global Fund" />
                        </div>
                        <div>
                            <x-input-label value="Grant Title" />
                            <input type="text" name="title" class="w-full border-gray-300 rounded-md" required placeholder="e.g. Community Health Initiative" />
                        </div>
                        <div>
                            <x-input-label value="Amount (KSh)" />
                            <input type="number" step="0.01" name="amount" class="w-full border-gray-300 rounded-md" required placeholder="1000000" />
                        </div>
                        <div>
                            <x-input-label value="Application Deadline" />
                            <input type="date" name="application_deadline" class="w-full border-gray-300 rounded-md" />
                        </div>
                        <div>
                            <x-input-label value="Status" />
                            <select name="status" class="w-full border-gray-300 rounded-md" required>
                                <option value="draft">Draft</option>
                                <option value="submitted">Submitted</option>
                                <option value="awarded">Awarded</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <x-primary-button class="w-full justify-center">Save Grant</x-primary-button>
                        </div>
                    </form>
                </div>

                <hr>

                <!-- Record Deliverable Form -->
                <div>
                    <h3 class="font-bold text-gray-900 mb-4">Add Grant Deliverable / Milestone</h3>
                    <form action="{{ route('admin.grants.deliverables.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @csrf
                        <div>
                            <x-input-label value="Select Grant" />
                            <select name="grant_id" class="w-full border-gray-300 rounded-md" required>
                                <option value="">-- Choose Grant --</option>
                                @foreach($grants as $g)
                                    <option value="{{ $g->id }}">{{ $g->funder_name }} - {{ $g->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label value="Deliverable Title" />
                            <input type="text" name="title" class="w-full border-gray-300 rounded-md" required placeholder="e.g. Q1 Monitoring Report" />
                        </div>
                        <div>
                            <x-input-label value="Due Date" />
                            <input type="date" name="due_date" class="w-full border-gray-300 rounded-md" required />
                        </div>
                        <div>
                            <x-input-label value="Status" />
                            <select name="status" class="w-full border-gray-300 rounded-md" required>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="md:col-span-4 flex justify-end">
                            <x-primary-button>Add Deliverable</x-primary-button>
                        </div>
                    </form>
                </div>

                <hr>

                <!-- Grant Directory List -->
                <div>
                    <h3 class="font-bold text-gray-900 mb-4">Active Grants & Deliverables</h3>
                    <div class="space-y-6">
                        @foreach($grants as $grant)
                            <div class="border rounded-lg p-5 bg-gray-50">
                                <div class="flex justify-between items-center mb-2">
                                    <div>
                                        <h4 class="text-lg font-bold text-indigo-900">{{ $grant->title }}</h4>
                                        <p class="text-sm text-gray-600">Funder: <strong>{{ $grant->funder_name }}</strong> | Amount: <strong class="text-green-600">KSh {{ number_format($grant->amount, 2) }}</strong></p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-3 py-1 text-xs font-bold rounded-full uppercase {{ $grant->status === 'awarded' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $grant->status }}
                                        </span>
                                        <a href="{{ route('admin.grants.export.report', $grant->id) }}" class="px-3 py-1 bg-indigo-600 text-white text-xs font-semibold rounded hover:bg-indigo-700">
                                            Export Regulator Report
                                        </a>
                                    </div>
                                </div>

                                <!-- Deliverables Table -->
                                <div class="mt-4">
                                    <h5 class="text-xs font-bold uppercase text-gray-500 mb-2">Deliverables</h5>
                                    @if($grant->deliverables->isEmpty())
                                        <p class="text-xs text-gray-400 italic">No deliverables added yet.</p>
                                    @else
                                        <ul class="divide-y divide-gray-200 bg-white rounded border">
                                            @foreach($grant->deliverables as $deliv)
                                                <li class="p-3 text-sm flex justify-between items-center">
                                                    <span>{{ $deliv->title }} (Due: {{ $deliv->due_date->format('M d, Y') }})</span>
                                                    <span class="text-xs font-bold uppercase px-2 py-0.5 rounded {{ $deliv->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                                        {{ str_replace('_', ' ', $deliv->status) }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tab 2: Document Storage -->
            <div x-show="tab === 'documents'" x-cloak class="bg-white p-6 rounded-b-lg shadow-sm space-y-6">
                <h3 class="font-bold text-gray-900 mb-4">Upload Proposals & Compliance Documents</h3>
                <form action="{{ route('admin.grants.documents.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @csrf
                    <div>
                        <x-input-label value="Select Grant" />
                        <select name="grant_id" class="w-full border-gray-300 rounded-md" required>
                            <option value="">-- Choose Grant --</option>
                            @foreach($grants as $g)
                                <option value="{{ $g->id }}">{{ $g->funder_name }} - {{ $g->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label value="Document Title" />
                        <input type="text" name="title" class="w-full border-gray-300 rounded-md" required placeholder="e.g. Approved Proposal PDF" />
                    </div>
                    <div>
                        <x-input-label value="Category" />
                        <select name="category" class="w-full border-gray-300 rounded-md" required>
                            <option value="proposal">Proposal</option>
                            <option value="compliance">Compliance Document</option>
                            <option value="financial_report">Financial Report</option>
                            <option value="narrative_report">Narrative Report</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label value="Select File" />
                        <input type="file" name="document" class="w-full border-gray-300 rounded-md" required />
                    </div>
                    <div class="md:col-span-4 flex justify-end">
                        <x-primary-button>Upload File</x-primary-button>
                    </div>
                </form>

                <hr>

                <h3 class="font-bold text-gray-900 mb-4">Stored Documents</h3>
                <div class="space-y-4">
                    @foreach($grants as $grant)
                        @foreach($grant->documents as $doc)
                            <div class="p-4 border rounded-md flex justify-between items-center bg-gray-50">
                                <div>
                                    <div class="font-bold text-gray-800">{{ $doc->title }}</div>
                                    <div class="text-xs text-gray-500">
                                        Grant: {{ $grant->title }} | Category: <span class="uppercase font-semibold">{{ str_replace('_', ' ', $doc->category) }}</span>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="px-3 py-1 bg-indigo-600 text-white text-xs font-semibold rounded hover:bg-indigo-700">
                                    View / Download
                                </a>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>