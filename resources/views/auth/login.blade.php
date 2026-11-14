<x-guest-layout>
    <h1 class="text-2xl font-bold text-gray-800">Bienvenido de nuevo</h1>
    <p class="text-sm text-gray-500 mb-6">Inicia sesión para administrar el parque.</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Correo -->
        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                          :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Contraseña -->
        <div class="mt-4">
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Recordarme -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Recordarme</span>
            </label>
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">Iniciar sesión</x-primary-button>
        </div>

        @if (Route::has('password.request'))
            <p class="mt-4 text-center text-sm">
                <a class="text-indigo-600 hover:underline" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            </p>
        @endif

        <p class="mt-4 text-center text-sm text-gray-600">
            ¿No tienes cuenta?
            <a class="text-indigo-600 font-semibold hover:underline" href="{{ route('register') }}">Regístrate</a>
        </p>
    </form>
</x-guest-layout>
