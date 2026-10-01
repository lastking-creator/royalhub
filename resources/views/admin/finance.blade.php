<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Financial Management Suite') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ tab: 'donations' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
            @endif

            <!-- Navigation Tabs & Export Buttons -->
            <div class="flex justify-between items-center border-b bg-white p-4 rounded-t-lg shadow-sm">
                <div class="flex space-x-4">
                    <button @click="tab = 'donations'" :class="tab === 'donations' ? 'text-indigo-600 border-b-2 border-indigo-600 font-bold' : 'text-gray-500'" class="pb-2">Donations & Member Contributions</button>
                    <button @click="tab = 'donors'" :class="tab === 'donors' ? 'text-indigo-600 border-b-2 border-indigo-600 font-bold' : 'text-gray-500'" class="pb-2">Donor Directory</button>
                    <button @click="tab = 'expenses'" :class="tab === 'expenses' ? 'text-indigo-600 border-b-2 border-indigo-600 font-bold' : 'text-gray-500'" class="pb-2">Expenses & Budget</button>
                </div>
                <div class="space-x-2">
                    <a href="{{ route('admin.finance.export', 'donations') }}" class="px-3 py-1.5 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700">Export Contributions CSV</a>
                    <a href="{{ route('admin.finance.export', 'expenses') }}" class="px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700">Export Expenses CSV</a>
                </div>
            </div>

            <!-- Tab 1: Separate Entry Forms -->
            <div x-show="tab === 'donations'" class="bg-white p-6 rounded-b-lg shadow-sm space-y-8">
                
                <!-- SECTION 1: REGISTERED MEMBER CONTRIBUTIONS -->
                <div class="border-b pb-6">
                    <h3 class="font-bold text-lg text-indigo-700 mb-4">Record Member Contribution (Dues, Welfare, Pledges)</h3>
                    
                    <form action="{{ route('admin.donations.store-member') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @csrf
                        <div>
                            <x-input-label value="Select Member *" />
                            <select name="user_id" required class="w-full border-gray-300 rounded-md">
                                <option value="">-- Choose Member --</option>
                                @foreach($members as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }} (#{{ $m->id }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label value="Amount (KSh) *" />
                            <input type="number" step="0.01" name="amount" required placeholder="1000" class="w-full border-gray-300 rounded-md" />
                        </div>

                        <div>
                            <x-input-label value="Purpose / Category" />
                            <input type="text" name="purpose" placeholder="e.g. Monthly Dues, Welfare Fund" class="w-full border-gray-300 rounded-md" />
                        </div>

                        <div>
                            <x-input-label value="Target Project (Optional)" />
                            <select name="project_id" class="w-full border-gray-300 rounded-md">
                                <option value="">-- General Fund --</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label value="Payment Method *" />
                            <select name="payment_method" required class="w-full border-gray-300 rounded-md">
                                <option value="mpesa">M-Pesa</option>
                                <option value="cash">Cash</option>
                                <option value="bank">Bank Transfer</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label value="Transaction Ref / Code" />
                            <input type="text" name="reference_number" placeholder="e.g. QX1234567" class="w-full border-gray-300 rounded-md" />
                        </div>

                        <div>
                            <x-input-label value="Date Paid *" />
                            <input type="date" name="donated_at" value="{{ date('Y-m-d') }}" required class="w-full border-gray-300 rounded-md" />
                        </div>

                        <div class="md:col-span-3 flex justify-end">
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">Record Member Contribution</x-primary-button>
                        </div>
                    </form>

                    <!-- Member Ledger Table -->
                    <h4 class="font-bold text-gray-700 mt-6 mb-2">Recent Member Contributions Ledger</h4>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-indigo-50 text-indigo-900">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs uppercase">Member Name</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Amount (KSh)</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Method / Code</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Purpose</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Assigned Project</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            @forelse($memberContributions as $mc)
                                <tr>
                                    <td class="px-4 py-2 font-bold">{{ $mc->user->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 text-emerald-600 font-bold">KSh {{ number_format($mc->amount, 2) }}</td>
                                    <td class="px-4 py-2 text-xs uppercase">{{ $mc->payment_method }} ({{ $mc->reference_number ?? 'No Code' }})</td>
                                    <td class="px-4 py-2">{{ $mc->purpose ?? 'General Dues' }}</td>
                                    <td class="px-4 py-2">{{ $mc->project->title ?? 'General Fund' }}</td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($mc->donated_at)->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="p-4 text-center text-gray-500">No member contributions recorded yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- SECTION 2: EXTERNAL DONOR DONATIONS -->
                <div>
                    <h3 class="font-bold text-lg text-emerald-700 mb-4">Record External Donor Grant / Contribution</h3>
                    
                    <form action="{{ route('admin.donations.store-donor') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @csrf
                        <div>
                            <x-input-label value="Select Donor *" />
                            <select name="donor_id" required class="w-full border-gray-300 rounded-md">
                                <option value="">-- Choose External Donor --</option>
                                @foreach($donors as $donor)
                                    <option value="{{ $donor->id }}">{{ $donor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label value="Type *" />
                            <select name="type" required class="w-full border-gray-300 rounded-md">
                                <option value="cash">Cash Grant / Funds</option>
                                <option value="in-kind">In-Kind (Equipment / Goods)</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label value="Amount / Estimated Value (KSh)" />
                            <input type="number" step="0.01" name="amount" placeholder="50000" class="w-full border-gray-300 rounded-md" />
                        </div>

                        <div>
                            <x-input-label value="Target Project" />
                            <select name="project_id" class="w-full border-gray-300 rounded-md">
                                <option value="">-- General Fund --</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label value="Date Donated *" />
                            <input type="date" name="donated_at" value="{{ date('Y-m-d') }}" required class="w-full border-gray-300 rounded-md" />
                        </div>

                        <div>
                            <x-input-label value="In-Kind Goods Description" />
                            <input type="text" name="description" placeholder="e.g. 10 Laptops, Medical Supplies" class="w-full border-gray-300 rounded-md" />
                        </div>

                        <div class="md:col-span-3 flex justify-end">
                            <x-primary-button class="bg-emerald-600 hover:bg-emerald-700">Record Donor Grant</x-primary-button>
                        </div>
                    </form>

                    <!-- Donor Grants Table -->
                    <h4 class="font-bold text-gray-700 mt-6 mb-2">External Donor Contributions History</h4>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-emerald-50 text-emerald-900">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs uppercase">Donor Entity</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Type</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Value (KSh)</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Assigned Project</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Details / Description</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            @forelse($donorDonations as $dd)
                                <tr>
                                    <td class="px-4 py-2 font-bold">{{ $dd->donor->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 uppercase font-bold text-xs">{{ $dd->type }}</td>
                                    <td class="px-4 py-2 font-bold text-emerald-600">KSh {{ number_format($dd->amount, 2) }}</td>
                                    <td class="px-4 py-2">{{ $dd->project->title ?? 'General Fund' }}</td>
                                    <td class="px-4 py-2 text-gray-500">{{ $dd->description ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($dd->donated_at)->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="p-4 text-center text-gray-500">No donor grants recorded yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 2: Donor Management -->
            <div x-show="tab === 'donors'" x-cloak class="bg-white p-6 rounded-b-lg shadow-sm">
                <form action="{{ route('admin.donors.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    @csrf
                    <div>
                        <x-input-label value="Full Name / Entity" />
                        <input type="text" name="name" class="w-full border-gray-300 rounded-md" required />
                    </div>
                    <div>
                        <x-input-label value="Organization (Optional)" />
                        <input type="text" name="organization" class="w-full border-gray-300 rounded-md" />
                    </div>
                    <div>
                        <x-input-label value="Email" />
                        <input type="email" name="email" class="w-full border-gray-300 rounded-md" />
                    </div>
                    <div>
                        <x-input-label value="Phone" />
                        <input type="text" name="phone" class="w-full border-gray-300 rounded-md" />
                    </div>
                    <div class="md:col-span-2 flex justify-end">
                        <x-primary-button>Add Donor Profile</x-primary-button>
                    </div>
                </form>

                <h4 class="font-bold text-gray-700 mb-2">Donor Profiles & Giving History</h4>
                <div class="space-y-4">
                    @foreach($donors as $donor)
                        <div class="border p-4 rounded-md">
                            <div class="flex justify-between font-bold">
                                <span>{{ $donor->name }} ({{ $donor->organization ?? 'Individual' }})</span>
                                <span class="text-indigo-600">Total Given: KSh {{ number_format($donor->donations->sum('amount'), 2) }}</span>
                            </div>
                            <p class="text-xs text-gray-500">Email: {{ $donor->email ?? 'N/A' }} | Phone: {{ $donor->phone ?? 'N/A' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab 3: Financial Summary & Expense Tracking -->
            <div x-show="tab === 'expenses'" x-cloak class="bg-white p-6 rounded-b-lg shadow-sm">
                <h3 class="font-medium text-gray-900 mb-4">Project Funding & Expenses Overview</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    @foreach($projects as $proj)
                        @php 
                            $totalRaised = $proj->donations->sum('amount');
                            $actuals = $proj->expenses->sum('amount');
                            $netBalance = $totalRaised - $actuals;
                        @endphp
                        <div class="border p-4 rounded-md bg-gray-50">
                            <div class="font-bold text-lg mb-2 text-indigo-700">{{ $proj->title }}</div>
                            <div class="text-sm grid grid-cols-2 md:grid-cols-4 gap-2">
                                <div>Allocated Budget: <br><strong class="text-gray-800">KSh {{ number_format($proj->budget, 2) }}</strong></div>
                                <div>Total Raised: <br><strong class="text-emerald-600">KSh {{ number_format($totalRaised, 2) }}</strong></div>
                                <div>Total Spent: <br><strong class="text-red-600">KSh {{ number_format($actuals, 2) }}</strong></div>
                                <div>Net Balance: <br><strong class="{{ $netBalance < 0 ? 'text-red-600' : 'text-blue-600' }}">KSh {{ number_format($netBalance, 2) }}</strong></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h3 class="font-medium text-gray-900 mb-4">Record Project Expense</h3>
                <form action="{{ route('admin.expenses.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <x-input-label value="Select Project" />
                        <select name="project_id" class="w-full border-gray-300 rounded-md" required>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label value="Expense Title" />
                        <input type="text" name="title" class="w-full border-gray-300 rounded-md" required placeholder="e.g. Venue Rental" />
                    </div>
                    <div>
                        <x-input-label value="Category" />
                        <input type="text" name="category" class="w-full border-gray-300 rounded-md" required placeholder="Logistics" />
                    </div>
                    <div>
                        <x-input-label value="Amount (KSh)" />
                        <input type="number" step="0.01" name="amount" class="w-full border-gray-300 rounded-md" required />
                    </div>
                    <div>
                        <x-input-label value="Date" />
                        <input type="date" name="expensed_at" class="w-full border-gray-300 rounded-md" required />
                    </div>
                    <div class="md:col-span-3 flex justify-end">
                        <x-primary-button>Log Expense</x-primary-button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>