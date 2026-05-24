<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8"
        x-data="{
            selectedType: '{{ old('product_type_id', $category->product_type_id ?? '') }}',
            selectedParent: '{{ old('parent_id', $category->parent_id ?? '') }}',
            allCategories: {{ $allCategories->toJson() }}
        }">

        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <h2 class="text-2xl font-bold mb-6">
                {{ isset($category) ? 'Edit' : 'Create' }} Category
            </h2>

            <form method="POST"
                action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
                enctype="multipart/form-data">

                @csrf
                @isset($category) @method('PUT') @endisset

                <!-- Name Field -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Category Name</label>
                    <input type="text" name="name"
                        value="{{ old('name', $category->name ?? '') }}"
                        class="mt-1 block w-full rounded-md shadow-sm @error('name') border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:border-teal-500 focus:ring-teal-500 @enderror" required>

                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Type Dropdown -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Product Type</label>
                    <select name="product_type_id"
                        x-model="selectedType"
                        @change="selectedParent = ''"
                        class="mt-1 block w-full rounded-md shadow-sm @error('product_type_id') border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:border-teal-500 focus:ring-teal-500 @enderror">
                        <option value="">-- Select Type --</option>
                        @foreach($productTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>

                    @error('product_type_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Parent Category Dropdown -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Parent Category (Optional)</label>
                    <select name="parent_id"
                        x-model="selectedParent"
                        class="mt-1 block w-full rounded-md shadow-sm @error('parent_id') border-red-500 text-red-900 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:border-teal-500 focus:ring-teal-500 @enderror">
                        <option value="">-- None (Top Level) --</option>

                        <template x-for="cat in allCategories.filter(c => c.id != '{{ $category->id ?? 0 }}' && c.product_type_id == selectedType)" :key="cat.id">
                            <option :value="cat.id" x-text="cat.name" :selected="cat.id == selectedParent"></option>
                        </template>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Select a Product Type first to see available parents.</p>

                    @error('parent_id')
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

                    @isset($category->image)
                    <div class="mt-2">
                        <img src="{{ Storage::url($category->image) }}" class="w-16 h-16 rounded object-cover border">
                        <p class="text-xs text-gray-500 mt-1">Leave blank to keep current image.</p>
                    </div>
                    @endisset
                </div>

                <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">
                    {{ isset($category) ? 'Update' : 'Save' }} Category
                </button>
            </form>
        </div>
    </div>
</x-app-layout>