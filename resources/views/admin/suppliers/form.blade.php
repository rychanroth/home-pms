<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <h2 class="text-2xl font-bold mb-6">
            {{ isset($supplier) ? 'Edit' : 'Create' }} supplier
        </h2>

        <form method="POST"
            action="{{ isset($supplier) ? route('admin.suppliers.update', $supplier) : route('admin.suppliers.store') }}"
            enctype="multipart/form-data">

            @csrf
            @isset($supplier) @method('PUT') @endisset

            <!-- Name Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Supplier Name</label>
                <input type="text" name="name"
                    value="{{ $supplier->name ?? old('name') }}"
                    class="mt-1 block w-full rounded-md shadow-sm @error('name') border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:border-teal-500 focus:ring-teal-500 @enderror" required>

                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" name="phone"
                    value="{{ $supplier->phone ?? old('phone') }}"
                    class="mt-1 block w-full rounded-md shadow-sm @error('phone') border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:border-teal-500 focus:ring-teal-500 @enderror" required>

                @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Address</label>
                <input type="text" name="address"
                    value="{{ $supplier->address ?? old('address') }}"
                    class="mt-1 block w-full rounded-md shadow-sm @error('address') border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:border-teal-500 focus:ring-teal-500 @enderror" required>

                @error('address')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>


            <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">
                {{ isset($supplier) ? 'Update' : 'Save' }} supplier
            </button>
        </form>
    </div>
</x-app-layout>