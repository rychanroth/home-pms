<x-guest-layout class="bg-teal-50 text-teal-900 antialiased">

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="w-full max-w-md mx-auto px-4 py-12 sm:px-8 lg:px-10">
        <div class="bg-white rounded-2xl shadow-sm border border-teal-100 p-8">

            <!-- Header -->
            <div class="flex items-center justify-center mb-6">
                <div class="p-3 bg-teal-50 rounded-full ring-8 ring-1 ring-teal-100">
                    <x-heroicon-o-user-group class="w-8 h-8 text-teal-600" />
                </div>
            </div>

            <h2 class="text-2xl font-bold text-center text-teal-900 mb-1">Welcome back</h2>
            <p class="text-sm text-teal-600 text-center mb-6">Sign in to access the cash register and inventory management.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-teal-700" />
                    <x-text-input id="email"
                        class="block mt-1 w-full rounded-lg border-gray-200 text-teal-900 placeholder:text-teal-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500 focus:ring-offset-2"
                        type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" class="text-teal-700" />
                    <x-text-input id="password"
                        class="block mt-1 w-full rounded-lg border-gray-200 text-teal-900 placeholder:text-teal-400 focus:border-teal-500 focus:ring-2 focus:regex:^[a-zA-Z0-9.]+$/ focus:ring-offset-2"
                        type="password"
                        name="password"
                        required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-2 focus:ring-teal-500 bg-teal-50 checked:bg-teal-600 checked:text-white transition-colors"
                            name="remember">
                        <span class="ms-2 text-sm text-teal-700">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-6">
                    @if (Route::has('password.request'))
                    <a class="text-sm text-teal-600 hover:text-teal-900 underline" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                    @endif

                    <x-primary-button class="bg-teal-600 hover:bg-teal-700 focus:ring-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-teal-500">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
</x-guest-layout>