<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-dark-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 shadow-sm sm:rounded-lg p-6 text-gray-300">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-yellow-400 text-black rounded-xl p-6 shadow-md hover:scale-105 transition-transform duration-300">
                        <h3 class="text-lg font-semibold mb-1">Total Clients</h3>
                        <p class="text-3xl font-bold">{{ $totalClients }}</p>
                    </div>
                    <div class="bg-red-400 text-black rounded-xl p-6 shadow-md hover:scale-105 transition-transform duration-300">
                        <h3 class="text-lg font-semibold mb-1">Total Brokers</h3>
                        <p class="text-3xl font-bold">{{ $totalBrk }}</p>
                    </div>
                    <div class="bg-green-400 text-black rounded-xl p-6 shadow-md hover:scale-105 transition-transform duration-300">
                        <h3 class="text-lg font-semibold mb-1">Active Clients</h3>
                        <p class="text-3xl font-bold">{{ $totalActive }}</p>
                    </div>
                    <div class="bg-gray-300 text-black rounded-xl p-6 shadow-md hover:scale-105 transition-transform duration-300">
                        <h3 class="text-lg font-semibold mb-1">Inactive Clients</h3>
                        <p class="text-3xl font-bold">{{ $totalInactive }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Clients Per City -->
                    <div class="bg-gray-800 rounded-lg p-6">
                        <h3 class="text-xl font-semibold mb-4">Clients per City</h3>
                        <canvas id="clientsPerCityChart"></canvas>
                    </div>

                    <!-- Clients Per State -->
                    <div class="bg-gray-800 rounded-lg p-6">
                        <h3 class="text-xl font-semibold mb-4">Clients per State</h3>
                        <canvas id="clientsPerStateChart"></canvas>
                    </div>

                    <!-- Clients by Status -->
                    <div class="bg-gray-800 rounded-lg p-6">
                        <h3 class="text-xl font-semibold mb-4">Clients by Status</h3>
                        <canvas id="clientsByStatusChart"  style="width: 50%; height: 400px;"></canvas>
                    </div>

                    <!-- Clients by Broker -->
                    <div class="bg-gray-800 rounded-lg p-6">
                        <h3 class="text-xl font-semibold mb-4">Clients by Broker</h3>
                        <canvas id="clientsByBrkChart" style="width: 100%; height: 400px;"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const clientsPerCityLabels = @json($clientsPerCity->pluck('CITY'));
        const clientsPerCityData = @json($clientsPerCity->pluck('total'));

        const clientsPerStateLabels = @json($clientsPerState->pluck('STATE'));
        const clientsPerStateData = @json($clientsPerState->pluck('total'));

        const clientsByStatusLabels = @json($clientsByStatus->pluck('Active_Inactive'));
        const clientsByStatusData = @json($clientsByStatus->pluck('total'));

        const clientsByBrkLabels = @json(array_column($clientsByBrkMerged, 'brk'));
        const activeData = @json(array_column($clientsByBrkMerged, 'active'));
        const inactiveData = @json(array_column($clientsByBrkMerged, 'inactive'));

        const darkOptions = {
            responsive: true,
            plugins: {
                legend: { labels: { color: '#ccc' } },
                tooltip: {
                    backgroundColor: '#1f2937',
                    titleColor: '#f3f4f6',
                    bodyColor: '#e5e7eb',
                }
            },
            scales: {
                x: { ticks: { color: '#ccc' }, grid: { color: '#374151' } },
                y: { ticks: { color: '#ccc' }, grid: { color: '#374151' }, beginAtZero: true }
            }
        };

        // Clients per City
        new Chart(document.getElementById('clientsPerCityChart'), {
            type: 'bar',
            data: {
                labels: clientsPerCityLabels,
                datasets: [{
                    label: 'Clients',
                    data: clientsPerCityData,
                    backgroundColor: '#3b82f6',
                    borderRadius: 5
                }]
            },
            options: darkOptions
        });

        // Clients per State
        new Chart(document.getElementById('clientsPerStateChart'), {
            type: 'bar',
            data: {
                labels: clientsPerStateLabels,
                datasets: [{
                    label: 'Clients',
                    data: clientsPerStateData,
                    backgroundColor: '#10b981',
                    borderRadius: 5
                }]
            },
            options: darkOptions
        });

        // Clients by Status (Pie)
        new Chart(document.getElementById('clientsByStatusChart'), {
            type: 'pie',
            data: {
                labels: clientsByStatusLabels,
                datasets: [{
                    data: clientsByStatusData,
                    backgroundColor: ['#16a34a', '#dc2626']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { labels: { color: '#ccc' } }
                }
            }
        });

        // Clients by Broker (Horizontal Bar)
        new Chart(document.getElementById('clientsByBrkChart'), {
            type: 'bar',
            data: {
                labels: clientsByBrkLabels,
                datasets: [
                    {
                        label: 'Active Clients',
                        data: activeData,
                        backgroundColor: '#22c55e',  // lime green
                        borderRadius: 5,
                    },
                    {
                        label: 'Inactive Clients',
                        data: inactiveData,
                        backgroundColor: '#ef4444',  // red
                        borderRadius: 5,
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { color: '#eee' },
                        grid: { color: '#444' }
                    },
                    y: {
                        ticks: { color: '#eee' },
                        grid: { color: '#444' }
                    }
                },
                plugins: {
                    legend: {
                        labels: { color: '#eee' }
                    }
                }
            }
        });
    </script>
</x-app-layout>
