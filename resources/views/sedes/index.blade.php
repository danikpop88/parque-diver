<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Sedes</h2>
            <a href="{{ route('sedes.create') }}"
               class="px-4 py-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 rounded-md text-xs font-semibold uppercase tracking-widest">
                Nueva sede
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 rounded-md bg-green-100 text-green-800">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 rounded-md bg-red-100 text-red-800">{{ session('error') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 overflow-x-auto">
                <table id="tablaSedes" class="display stripe w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Atracciones</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sedes as $sede)
                            <tr>
                                <td>{{ $sede->nombre }}</td>
                                <td>{{ $sede->ubicacion }}</td>
                                <td>{{ $sede->atracciones_count }}</td>
                                <td class="text-right whitespace-nowrap">
                                    <a href="{{ route('sedes.edit', $sede) }}" class="text-indigo-600 underline mr-3">Editar</a>
                                    <form method="POST" action="{{ route('sedes.destroy', $sede) }}" class="inline"
                                          onsubmit="return confirm('¿Eliminar esta sede?')">
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
            $('#tablaSedes').DataTable({
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json' },
                pageLength: 10,
                columnDefs: [{ orderable: false, searchable: false, targets: -1 }]
            });
        });
    </script>
</x-app-layout>
