@csrf

<div>
    <x-input-label for="nombre" value="Nombre" />
    <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full"
        :value="old('nombre', $atraccion->nombre ?? '')" maxlength="150" required autofocus />
    <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
</div>

<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="edad_minima" value="Edad mínima (años)" />
        <x-text-input id="edad_minima" name="edad_minima" type="number" min="0" max="120" class="mt-1 block w-full"
            :value="old('edad_minima', $atraccion->edad_minima ?? '')" required />
        <x-input-error :messages="$errors->get('edad_minima')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="estatura_minima" value="Estatura mínima (metros)" />
        <x-text-input id="estatura_minima" name="estatura_minima" type="number" step="0.01" min="0" max="3" class="mt-1 block w-full"
            :value="old('estatura_minima', $atraccion->estatura_minima ?? '')" required />
        <x-input-error :messages="$errors->get('estatura_minima')" class="mt-2" />
    </div>
</div>

<div class="mt-4">
    <x-input-label for="id_sede" value="Sede" />
    <select id="id_sede" name="id_sede" required
        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
        <option value="">Seleccione una sede</option>
        @foreach ($sedes as $sede)
            <option value="{{ $sede->id_sede }}"
                @selected(old('id_sede', $atraccion->id_sede ?? '') == $sede->id_sede)>
                {{ $sede->nombre }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('id_sede')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="imagen" value="Imagen (opcional, máx. 2 MB)" />
    @if (!empty($atraccion->imagen))
        <img src="{{ asset('storage/' . $atraccion->imagen) }}" alt="Imagen actual" class="mt-2 h-24 rounded-md">
    @endif
    <input id="imagen" name="imagen" type="file" accept="image/*"
        class="mt-2 block w-full text-sm text-gray-700 dark:text-gray-300">
    <x-input-error :messages="$errors->get('imagen')" class="mt-2" />
</div>

<div class="mt-6 flex items-center gap-4">
    <x-primary-button>Guardar</x-primary-button>
    <a href="{{ route('atracciones.index') }}" class="text-sm text-gray-600 dark:text-gray-400 underline">Cancelar</a>
</div>
