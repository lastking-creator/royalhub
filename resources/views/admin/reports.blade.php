<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports & Analytics Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter & Export Toolbar -->
            <div class="bg-white p-6 rounded-lg shadow-sm flex flex-wrap justify-between items-center gap-4">
                <form action="{{ route('admin.reports') }}" method="GET" class="flex items-center space-x-3">
                    <input type="date" name="start_date" value="{{ $startDate }}" class="border-gray-300 rounded-md text-sm" />
                    <span class="text-gray-500">to</span>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="border-gray-300 rounded-md text-sm" />
                    <x-primary-button>Filter</x-primary-button>
                </form>

                <div class="flex space-x-2">
                    <a href="{{ route('admin.reports.export', ['format' => 'csv', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-md text-xs font-bold uppercase hover:bg-emerald-700">Export CSV/Excel</a>
                    <a href="{{ route('admin.reports.export', ['format' => 'pdf', 'start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-xs font-bold uppercase hover:bg-indigo-700">Export PDF</a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-lg border-l-4 border-indigo-500 shadow-sm">
                    <div class="text-xs text-gray-500 font-bold uppercase">Total Income</div>
                    <div class="text-xl font-extrabold text-indigo-600">KSh {{ number_format($totalDonations, 2) }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg border-l-4 border-red-500 shadow-sm">
                    <div class="text-xs text-gray-500 font-bold uppercase">Total Expenses</div>
                    <div class="text-xl font-extrabold text-red-600">KSh {{ number_format($totalExpenses, 2) }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg border-l-4 border-emerald-500 shadow-sm">
                    <div class="text-xs text-gray-500 font-bold uppercase">Net Balance</div>
                    <div class="text-xl font-extrabold text-emerald-600">KSh {{ number_format($netBalance, 2) }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg border-l-4 border-blue-500 shadow-sm">
                    <div class="text-xs text-gray-500 font-bold uppercase">Approved Members</div>
                    <div class="text-xl font-extrabold text-blue-600">{{ $approvedMembers }}</div>
                </div>
            </div>

            <!-- Chart.js Trends -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4">Financial Trends & Monthly Breakdown</h3>
                <div style="height: 320px;">
                    <canvas id="financialChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('financialChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Income (KSh)',
                        data: @json($monthlyDonations),
                        backgroundColor: 'rgba(79, 70, 229, 0.7)'
                    },
                    {
                        label: 'Expenses (KSh)',
                        data: @json($monthlyExpenses),
                        backgroundColor: 'rgba(239, 68, 68, 0.7)'
                    }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    </script>
</x-app-layout>