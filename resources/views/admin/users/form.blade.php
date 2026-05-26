@use(App\Enums\UserRole)
<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <h2 class="text-2xl font-bold mb-6">{{ isset($user) ? 'Edit' : 'Create' }} User</h2>

        <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}">
            @csrf
            @isset($user) @method('PUT') @endisset

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" value="{{ $user->name ?? old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('name') border-red-500 @enderror" required>
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ $user->email ?? old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('email') border-red-500 @enderror" required>
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password {{ isset($user) ? '(Leave blank to keep current)' : '' }}</label>
                        <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('password') border-red-500 @enderror" {{ isset($user) ? '' : 'required' }}>
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('role') border-red-500 @enderror" required>
                            @foreach(UserRole::options() as $value => $label)
                            <option value="{{ $value }}" {{ ($user->role->value ?? old('role')) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded hover:bg-teal-700">{{ isset($user) ? 'Update' : 'Create' }} User</button>
                </div>
        </form>
    </div>
</x-app-layout>