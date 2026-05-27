<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf- mutation-check">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Aeterna Pharmacy') }}</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* Minimal fallback if Vite hasn't been run yet */
                body { font-family: ui-sans-serif, system-ui, sans-serif; background-color: #f0fdfa; color: #134e4a; }
                .btn-default { background-color: #f0fdfa; color: #115e59; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; font-size: 0.875rem; font-weight: 500; display: inline-block; transition: background-color 150ms ease-in-out; }
                .btn-primary { background-color: #0f766e; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; font-size: 0.875rem; font-weight: 600; display: inline-block; transition: background-color 150ms ease-in-out; }
                .btn-primary:hover { background-color: #065f46; }
            </style>
        @endif
    </head>
    <body class="bg-teal-50 text-teal-900 flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        
        <!-- Header Links -->
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-default">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-default">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-default">Register</a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <!-- Main Content -->
        <div class="flex items-center justify-center w-full lg:grow">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
                
                <!-- Left Column: Instructions -->
                <div class="text-sm leading-relaxed flex-1 p-6 pb-6 lg:p-20 lg:pb-10 bg-white rounded-bl-lg lg:rounded-tl-lg shadow-sm">
                    <h1 class="mb-1 text-lg font-semibold text-teal-900">Welcome to Aeterna Pharmacy</h1>
                    <p class="mb-4 text-teal-600">
                        A minimal, immutable POS system for small home businesses. <br>
                        Here is the fastest way to get started:
                    </p>
                    
                    <ul class="flex flex-col mb-6 space-y-3">
                        <!-- Step 1 -->
                        <li class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-teal-100 flex items-center justify-center">
                                <x-heroicon-o-swatch class="w-3.5 h-3.5 text-teal-600" />
                            </div>
                            <span>
                                <span class="font-medium text-teal-800">Categorize Inventory:</span>
                                <span class="text-teal-600">Group items by Type and Category.</span>
                            </span>
                        </li>

                        <!-- Step 2 -->
                        <li class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-teal-100 flex items-center justify-center">
                                <x-heroicon-o-arrow-down-tray class="w-3.5 h-3.5 text-teal-600" />
                            </div>
                            <span>
                                <span class="font-medium text-teal-800">Record Stock In:</span>
                                <span class="text-teal-600">Log supplier deliveries.</span>
                            </span>
                        </li>

                        <!-- Step 3 -->
                        <li class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-teal-100 flex items-center justify-center">
                                <x-heroicon-o-shopping-cart class="w-3.5 h-3.5 text-teal-600" />
                            </div>
                            <span>
                                <span class="font-medium text-teal-800">Process Sales:</span>
                                <span class="text-teal-600">Fast, atomic checkout.</span>
                            </span>
                        </li>

                        <!-- Step 4 -->
                        <li class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-teal-100 flex items-center justify-center">
                                <x-heroicon-o-lock-closed class="w-3.5 h-3.5 text-teal-600" />
                            </div>
                            <span>
                                <span class="font-medium text-teal-800">Immutable Ledger:</span>
                                <span class="text-teal-600">Changes are never edited, only offset.</span>
                            </span>
                        </li>
                    </ul>
                </div>

                <!-- Right Column: Visual -->
                <div class="bg-teal-600 relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg w-full lg:w-[438px] shrink-0 overflow-hidden flex items-center justify-center p-10">
                    <x-heroicon-o-building-storefront class="w-20 h-20 text-teal-100" />
                </div>
            </main>
        </div>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif

        <!-- Footer -->
        <p class="mt-6 text-sm text-teal-600">
            v{{ app()->version() }}
        </p>
    </body>
</html>