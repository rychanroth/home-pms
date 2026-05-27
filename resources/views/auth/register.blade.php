<x-guest-layout class="bg-teal-50 text-teal-900 antialiased">

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="w-full max-w-md mx-auto px-4 py-12 sm:px-8 lg:px-10">
        <div class="bg-white rounded-2xl shadow-sm border-teal-100 p-8">

            <!-- Header -->
            <div class="flex items-center justify-center mb-6">
                <div class="p-3 bg-teal-50 rounded-full ring-8 ring-1 ring-teal-100">
                    <x-heroicon-o-user-plus class="w-8 h-8 text-teal-600" />
                </div>
            </div>

            <h2 class="text-2xl font-bold text-teal-900 mb-1">Create an account</h2>
            <p class="text-sm text-teal-600 text-center mb-6">Join the system to start selling and managing inventory.</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="'Name'" />
                    <x-text-input id="name" class="block mt-1 w-full rounded-lg border-teal-200 text-teal-900 placeholder:text-teal-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500 focus:ring-offset-2"
                        type="text" name="name" :value="old('name')" required autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="'Email'" />
                    <x-text-input id="email" class="block mt-1 w-full rounded-lg border-teal-200 text-teal-900 placeholder:text-teal-400 focus:border-teal-500 focus:ring-2 focus:ring-2 focus:ring-offset-2"
                        type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="'Password'" />

                    <x-text-input id="password" class="block mt-1 w-full rounded-lg border-teal-200 text-teal-900 placeholder:text-teal-400 focus:border-teal-500 focus:ring-2 focus:ring-2 focus:ring-offset-2"
                        type="password"
                        name="password"
                        required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="'Confirm Password'" />

                    <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-lg border-teal-200 text-teal-900 placeholder:text-teal-400 focus:border-teal-500 focus:ring-2 focus:ring-2 focus:ring-offset-2"
                        type="password"
                        name="password_confirmation" required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600" />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a class="text-sm text-teal-600 hover:text-teal-900 underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500" href="{{ route('login') }}">
                        Already registered?
                    </a>

                    <x-primary-button class="bg-teal-600 hover:bg-teal-700 focus:ring-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        Register
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>