<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Atracciones</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('reportes.atracciones') }}"
                   class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md text-xs font-semibold uppercase tracking-widest">
                    Descargar PDF
                </a>
                <a href="{{ route('atracciones.create') }}"
                   class="px-4 py-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 rounded-md text-xs font-semibold uppercase tracking-widest">
                    Nueva atracción
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 rounded-md bg-green-100 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 overflow-x-auto">
                <table id="tablaAtracciones" class="display stripe w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Sede</th>
                            <th>Edad mín.</th>
                            <th>Estatura mín. (m)</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($atracciones as $atraccion)
                            <tr>
                                <td>
                                    @if ($atraccion->imagen)
                                        <img src="{{ asset('storage/' . $atraccion->imagen) }}" alt="" class="h-12 w-12 object-cover rounded">
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td>{{ $atraccion->nombre }}</td>
                                <td>{{ $atraccion->sede->nombre }}</td>
                                <td>{{ $atraccion->edad_minima }}</td>
                                <td>{{ $atraccion->estatura_minima }}</td>
                                <td class="text-right whitespace-nowrap">
                                    <a href="{{ route('atracciones.edit', $atraccion) }}" class="text-indigo-600 underline mr-3">Editar</a>
                                    <form method="POST" action="{{ route('atracciones.destroy', $atraccion) }}" class="inline"
                                          onsubmit="return confirm('¿Eliminar esta atracción?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 underline">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script>
        $(function () {
            $('#tablaAtracciones').DataTable({
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json' },
                pageLength: 10,
                columnDefs: [
                    { orderable: false, searchable: false, targets: [0, -1] }
                ]
            });
        });
    </script>
</x-app-layout>
