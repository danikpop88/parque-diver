<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Usuario activo --}}
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <p class="text-gray-800">
                    Hola, <strong>{{ Auth::user()->name }}</strong>
                    <span class="text-gray-500">({{ Auth::user()->email }})</span>
                </p>
            </div>

            {{-- Tarjetas resumen --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="p-6 bg-white shadow sm:rounded-lg">
                    <p class="text-sm text-gray-500">Sedes registradas</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalSedes }}</p>
                </div>
                <div class="p-6 bg-white shadow sm:rounded-lg">
                    <p class="text-sm text-gray-500">Atracciones registradas</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalAtracciones }}</p>
                </div>
            </div>

            {{-- Gráfica --}}
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-4">Atracciones por sede</h3>
                @if ($labels->isEmpty())
                    <p class="text-gray-500">Aún no hay sedes para graficar.</p>
                @else
                    <div class="relative h-80">
                        <canvas id="graficaAtracciones"></canvas>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        const canvas = document.getElementById('graficaAtracciones');
        if (canvas) {
            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Atracciones',
                        data: @json($datos),
                        backgroundColor: 'rgba(79, 70, 229, 0.7)',
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });
        }
    </script>
</x-app-layout>
