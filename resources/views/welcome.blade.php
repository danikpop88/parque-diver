<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Parque Diver') }} · Parque de atracciones</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-800 bg-gray-50">

        {{-- Barra superior --}}
        <header class="bg-white/90 backdrop-blur border-b border-gray-100 sticky top-0 z-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                <a href="/" class="flex items-center gap-2 font-bold text-lg">
                    <span class="text-2xl">🎡</span> Parque Diver
                </a>
                <nav class="flex items-center gap-3 text-sm">
                    <a href="#sedes" class="hidden sm:inline text-gray-600 hover:text-indigo-600">Sedes</a>
                    <a href="#atracciones" class="hidden sm:inline text-gray-600 hover:text-indigo-600">Atracciones</a>
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="px-4 py-2 rounded-md bg-indigo-600 text-white font-semibold">Ir al panel</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-gray-700 font-semibold">Iniciar sesión</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-4 py-2 rounded-md bg-indigo-600 text-white font-semibold">Registrarse</a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        {{-- Portada --}}
        <section class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 text-white">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-20 sm:py-28 text-center">
                <p class="text-6xl mb-4">🎢</p>
                <h1 class="text-4xl sm:text-5xl font-bold tracking-tight">Diversión para toda la familia</h1>
                <p class="mt-4 text-lg text-white/90 max-w-2xl mx-auto">
                    Administra las sedes y atracciones del parque, controla las restricciones de edad y estatura,
                    y consulta reportes en un solo lugar.
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="px-6 py-3 rounded-md bg-white text-indigo-700 font-semibold">Ir al panel</a>
                    @else
                        <a href="{{ route('register') }}"
                           class="px-6 py-3 rounded-md bg-white text-indigo-700 font-semibold">Crear cuenta</a>
                        <a href="{{ route('login') }}"
                           class="px-6 py-3 rounded-md border border-white/70 font-semibold">Iniciar sesión</a>
                    @endauth
                </div>
            </div>
        </section>

        {{-- Sedes --}}
        <section id="sedes" class="max-w-6xl mx-auto px-4 sm:px-6 py-16">
            <h2 class="text-2xl font-bold">Nuestras sedes</h2>
            <p class="text-gray-500 mt-1">Encuentra el parque más cercano a ti.</p>

            @if ($sedes->isEmpty())
                <p class="mt-6 text-gray-500">Pronto publicaremos nuestras sedes.</p>
            @else
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($sedes as $sede)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <p class="text-3xl">📍</p>
                            <h3 class="mt-2 font-semibold text-lg">{{ $sede->nombre }}</h3>
                            <p class="text-sm text-gray-500">{{ $sede->ubicacion }}</p>
                            <p class="mt-3 text-sm text-indigo-600 font-medium">
                                {{ $sede->atracciones_count }}
                                {{ $sede->atracciones_count === 1 ? 'atracción' : 'atracciones' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Atracciones --}}
        <section id="atracciones" class="bg-white border-y border-gray-100">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16">
                <h2 class="text-2xl font-bold">Atracciones destacadas</h2>
                <p class="text-gray-500 mt-1">Revisa las restricciones de cada una antes de visitarnos.</p>

                @if ($atracciones->isEmpty())
                    <p class="mt-6 text-gray-500">Pronto publicaremos nuestras atracciones.</p>
                @else
                    <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($atracciones as $atraccion)
                            <div class="rounded-xl overflow-hidden border border-gray-100 shadow-sm bg-white">
                                @if ($atraccion->imagen)
                                    <img src="{{ asset('storage/' . $atraccion->imagen) }}" alt="{{ $atraccion->nombre }}"
                                         class="h-44 w-full object-cover">
                                @else
                                    <div class="h-44 w-full flex items-center justify-center text-5xl bg-gradient-to-br from-indigo-100 to-pink-100">🎠</div>
                                @endif
                                <div class="p-5">
                                    <h3 class="font-semibold text-lg">{{ $atraccion->nombre }}</h3>
                                    <p class="text-sm text-gray-500">Sede {{ $atraccion->sede->nombre }}</p>
                                    <p class="mt-3 text-sm text-gray-700">
                                        Edad mínima: <strong>{{ $atraccion->edad_minima }} años</strong><br>
                                        Estatura mínima: <strong>{{ $atraccion->estatura_minima }} m</strong>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <footer class="py-8 text-center text-sm text-gray-500">
            © {{ date('Y') }} Parque Diver · Proyecto académico en Laravel
        </footer>
    </body>
</html>
