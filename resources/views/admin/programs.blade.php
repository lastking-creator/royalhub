<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects & Programs Suite') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'create-project' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tab Navigation -->
            <div class="mb-6 border-b border-gray-200 bg-white rounded-t-lg px-4 pt-4 shadow-sm">
                <nav class="-mb-px flex space-x-8">
                    <button @click="activeTab = 'create-project'" :class="activeTab === 'create-project' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        1. Create Project & Assign Manager
                    </button>
                    <button @click="activeTab = 'create-program'" :class="activeTab === 'create-program' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        2. Create Program under Project
                    </button>
                    <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Projects & Programs Hierarchy
                    </button>
                </nav>
            </div>

            <!-- Tab 1: Create Project -->
            <div x-show="activeTab === 'create-project'">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">New Project Setup</h3>
                    <form action="{{ route('admin.projects.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @csrf
                        <div>
                            <x-input-label for="title" :value="__('Project Title')" />
                            <x-text-input id="title" name="title" class="block mt-1 w-full" required placeholder="e.g. Coast Sanitation Drive" />
                        </div>

                        <div>
                            <x-input-label for="target_community" :value="__('Target Community / Beneficiary Group')" />
                            <x-text-input id="target_community" name="target_community" class="block mt-1 w-full" required placeholder="e.g. Likoni Ward Residents & CBO Youth" />
                        </div>

                        <div>
                            <x-input-label for="manager_id" :value="__('Assign CBO Project Manager')" />
                            <select id="manager_id" name="manager_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">-- Select CBO Member --</option>
                                @foreach($cboMembers as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="budget" :value="__('Project Budget (KSh)')" />
                            <x-text-input id="budget" name="budget" type="number" step="0.01" class="block mt-1 w-full" placeholder="500000" />
                        </div>

                        <!-- Project Timeframe Inputs -->
                        <div>
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input id="start_date" name="start_date" type="date" class="block mt-1 w-full" required />
                        </div>

                        <div>
                            <x-input-label for="end_date" :value="__('End Date')" />
                            <x-text-input id="end_date" name="end_date" type="date" class="block mt-1 w-full" required />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="description" :value="__('Project Description')" />
                            <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" placeholder="Provide context or core objectives for this project..."></textarea>
                        </div>

                        <div class="md:col-span-2 flex justify-end">
                            <x-primary-button>{{ __('Save Project') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tab 2: Create Sub-Program -->
            <div x-show="activeTab === 'create-program'" x-cloak>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Add Sub-Program to Existing Project</h3>
                    <form action="{{ route('admin.programs.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @csrf
                        <div>
                            <x-input-label for="project_id" :value="__('Select Parent Project')" />
                            <select id="project_id" name="project_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">-- Choose Project --</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->title }} (Manager: {{ $project->manager->name ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="name" :value="__('Program Name')" />
                            <x-text-input id="name" name="name" class="block mt-1 w-full" required placeholder="e.g. Tree Planting Phase 1" />
                        </div>

                        <div>
                            <x-input-label for="category" :value="__('Pillar')" />
                            <select id="category" name="category" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="Environment & Blue Economy">Environment & Blue Economy</option>
                                <option value="Youth Empowerment">Youth Empowerment</option>
                                <option value="GBV Response">GBV Response</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="description" :value="__('Program Description')" />
                            <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>

                        <div class="md:col-span-2 flex justify-end">
                            <x-primary-button>{{ __('Save & Attach Program') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tab 3: Hierarchy Overview -->
            <div x-show="activeTab === 'overview'" x-cloak>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Projects & Assigned Programs</h3>
                    <div class="space-y-6">
                        @forelse($projects as $project)
                            @php
                                $totalRaised = $project->donations->sum('amount');
                                $totalExpenses = $project->expenses->sum('amount');
                                $balance = $totalRaised - $totalExpenses;
                            @endphp
                            <div class="border border-gray-200 p-4 rounded-lg bg-gray-50">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="text-md font-bold text-indigo-700">{{ $project->title }}</h4>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Manager: {{ $project->manager->name ?? 'Unassigned' }}</span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm text-gray-600 mb-3">
                                    <p><strong>Target Group:</strong> {{ $project->target_community }}</p>
                                    <p><strong>Budget:</strong> KSh {{ number_format($project->budget, 2) }}</p>
                                    <p>
                                        <strong>Timeframe:</strong> 
                                        {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : 'N/A' }} 
                                        — 
                                        {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M d, Y') : 'N/A' }}
                                    </p>
                                </div>

                                <!-- Financial Metrics Banner -->
                                <div class="grid grid-cols-3 gap-2 p-3 bg-white rounded border text-xs font-semibold mb-3">
                                    <div>
                                        <span class="text-gray-500 uppercase">Total Raised</span>
                                        <div class="text-emerald-600 text-sm font-bold">KSh {{ number_format($totalRaised, 2) }}</div>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 uppercase">Total Spent</span>
                                        <div class="text-red-600 text-sm font-bold">KSh {{ number_format($totalExpenses, 2) }}</div>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 uppercase">Net Balance</span>
                                        <div class="text-sm font-bold {{ $balance < 0 ? 'text-red-600' : 'text-blue-600' }}">KSh {{ number_format($balance, 2) }}</div>
                                    </div>
                                </div>
                                
                                <div class="bg-white p-3 rounded border">
                                    <h5 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Attached Programs ({{ $project->programs->count() }})</h5>
                                    <ul class="list-disc list-inside text-sm text-gray-700">
                                        @forelse($project->programs as $prog)
                                            <li>{{ $prog->name }} <span class="text-xs text-gray-500">({{ $prog->category }})</span></li>
                                        @empty
                                            <li class="text-gray-400 italic list-none">No sub-programs created yet.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No projects registered.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>