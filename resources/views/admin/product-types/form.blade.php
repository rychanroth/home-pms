<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <h2 class="text-2xl font-bold mb-6">
            {{ isset($productType) ? 'Edit' : 'Create' }} Product Type
        </h2>
        
        <form method="POST" 
        action="{{ isset($productType) ? route('admin.product-types.update', $productType) : route('admin.product-types.store') }}" 
        enctype="multipart/form-data">
            
            @csrf
            @isset($productType) @method('PUT') @endisset

            <!-- Name Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Name (e.g., Tablet, Syrup)</label>
                <input type="text" name="name"
                class="mt-1 block w-full rounded-md shadow-sm @error('name') border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:border-teal-500 focus:ring-teal-500 @enderror" 
                value="{{ $productType->name ?? old('name') }}" required>
                
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Image</label>
                <input type="file" name="image" accept="image/*" 
                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 @error('image') text-red-500 @enderror">
                
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @isset($productType->image)
                    <div class="mt-2">
                        <img src="{{ Storage::url($productType->image) }}" class="w-16 h-16 rounded object-cover border">
                        <p class="text-xs text-gray-500 mt-1">Leave blank to keep current image.</p>
                    </div>
                @endisset
            </div>

            <!-- Expiration Checkbox -->
            <div class="mb-6 flex items-center">
                <input type="checkbox" name="requires_expiration" value="1"
                class="rounded border-gray-300 text-teal-600 shadow-sm"
                id="exp" {{ ($productType->requires_expiration ?? old('requires_expiration')) ? 'checked' : '' }}>
                <label for="exp" class="ml-2 text-sm text-gray-700">Requires Expiration Tracking</label>
            </div>

            <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">
                {{ isset($productType) ? 'Update' : 'Save' }} Type
            </button>
        </form>
    </div>
</x-app-layout>