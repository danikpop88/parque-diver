@csrf

<div>
    <x-input-label for="nombre" value="Nombre" />
    <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full"
        :value="old('nombre', $sede->nombre ?? '')" maxlength="30" required autofocus />
    <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="ubicacion" value="Ubicación" />
    <x-text-input id="ubicacion" name="ubicacion" type="text" class="mt-1 block w-full"
        :value="old('ubicacion', $sede->ubicacion ?? '')" maxlength="100" required />
    <x-input-error :messages="$errors->get('ubicacion')" class="mt-2" />
</div>

<div class="mt-6 flex items-center gap-4">
    <x-primary-button>Guardar</x-primary-button>
    <a href="{{ route('sedes.index') }}" class="text-sm text-gray-600 dark:text-gray-400 underline">Cancelar</a>
</div>
