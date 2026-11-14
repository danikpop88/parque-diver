<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Parque Diver') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500">

            <a href="/" class="mb-6 text-center">
                <span class="block text-5xl">🎡</span>
                <span class="block mt-2 text-2xl font-bold text-white tracking-wide">Parque Diver</span>
            </a>

            <div class="w-full sm:max-w-md px-8 py-8 bg-white shadow-2xl rounded-2xl">
                {{ $slot }}
            </div>

            <p class="mt-6 text-sm text-white/80">© {{ date('Y') }} Parque Diver · Proyecto académico en Laravel</p>
        </div>
    </body>
</html>
