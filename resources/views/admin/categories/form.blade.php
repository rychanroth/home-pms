<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <h2 class="text-2xl font-bold mb-6">
            {{ isset($category) ? 'Edit' : 'Create' }} Category
        </h2>

        <form method="POST"
            action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
            enctype="multipart/form-data">

            @csrf
            @isset($category) @method('PUT') @endisset

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Category Name</label>
                <input type="text" name="name" value="{{ $category->name ?? old('name') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <!-- PRODUCT TYPE DROPDOWN -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Product Type</label>
                <select name="product_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Select Type --</option>
                    @foreach($productTypes as $type)
                    <option value="{{ $type->id }}" {{ ($category->product_type_id ?? old('product_type_id')) == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- PARENT CATEGORY DROPDOWN -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Parent Category (Optional)</label>
                <select name="parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- None (Top Level) --</option>
                    @foreach($allCategories as $cat)
                    <!-- THE WALL PREVENTION: Don't let a category pick ITSELF as a parent! -->
                    @if(!isset($category) || $cat->id != $category->id)
                    <option value="{{ $cat->id }}" {{ ($category->parent_id ?? old('parent_id')) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endif
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Leave as 'None' to make this a main category.</p>
            </div>

            <!-- Image -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Image</label>
                <input type="file" name="image" accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
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
</x-app-layout>