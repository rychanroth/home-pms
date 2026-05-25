<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased h-screen overflow-hidden bg-gray-100">
    <div class="min-h-screen bg-gray-100 flex h-screen w-screen overflow-hidden">
        <!-- Conditional Navbar -->
        @if(auth()->check() && auth()->user()->isAdmin())
        @include('layouts.admin-navigation')
        @else
        @include('layouts.navigation')
        @endif

        <main class="flex-1 flex flex-col w-full overflow-y-auto">
            @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
            @endisset

            <div class="p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>

</html>