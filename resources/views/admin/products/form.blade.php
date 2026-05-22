<x-app-layout>
    <div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
        <h2 class="text-2xl font-bold mb-6">
            {{ isset($product) ? 'Edit' : 'Create' }} Product
        </h2>
        
        <form method="POST" 
        action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" 
        enctype="multipart/form-data">
            
            @csrf
            @isset($product) @method('PUT') @endisset

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- LEFT COLUMN -->
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" name="name" value="{{ $product->name ?? old('name') }}"
                        class="mt-1 block w-full rounded-md shadow-sm @error('name') border-red-500 @else border-gray-300 @enderror" required>
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Type & Category Dropdowns (Same pattern as Category form) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Product Type</label>
                        <select name="product_type_id" class="mt-1 block w-full rounded-md shadow-sm @error('product_type_id') border-red-500 @else border-gray-300 @enderror">
                            <option value="">-- Select Type --</option>
                            @foreach($productTypes as $type)
                                <option value="{{ $type->id }}" {{ ($product->product_type_id ?? old('product_type_id')) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('product_type_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" class="mt-1 block w-full rounded-md shadow-sm @error('category_id') border-red-500 @else border-gray-300 @enderror">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ ($product->category_id ?? old('category_id')) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Price & Stock -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price ($)</label>
                            <input type="number" step="0.01" name="selling_price" value="{{ $product->selling_price ?? old('selling_price') }}"
                            class="mt-1 block w-full rounded-md shadow-sm @error('selling_price') border-red-500 @else border-gray-300 @enderror" required>
                            @error('selling_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Stock Qty</label>
                            <input type="number" name="stock_quantity" value="{{ $product->stock_quantity ?? old('stock_quantity') }}"
                            class="mt-1 block w-full rounded-md shadow-sm @error('stock_quantity') border-red-500 @else border-gray-300 @enderror" required>
                            @error('stock_quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="space-y-4">
                    <!-- Suppliers Multi-Select -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Suppliers (Hold Ctrl/Cmd to select multiple)</label>
                        <select name="suppliers[]" multiple 
                                class="mt-1 block w-full rounded-md shadow-sm h-32 @error('suppliers') border-red-500 @else border-gray-300 @enderror">
                            @foreach($suppliers as $supplier)
                                <!-- Complex selected check for multi-select -->
                                <option value="{{ $supplier->id }}" 
                                    @if(isset($product) && $product->suppliers->contains($supplier->id)) selected @endif
                                    @if(!isset($product) && in_array($supplier->id, old('suppliers', []))) selected @endif>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('suppliers') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        @error('suppliers.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Base Unit -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Base Unit</label>
                        <select name="base_unit" class="mt-1 block w-full rounded-md shadow-sm border-gray-300">
                            @foreach(['tablet', 'capsule', 'bottle', 'box', 'sachet', 'tube'] as $unit)
                                <option value="{{ $unit }}" {{ ($product->base_unit ?? old('base_unit')) == $unit ? 'selected' : '' }}>{{ ucfirst($unit) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Expiration Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Expiration Date</label>
                        <input type="date" name="expiration_date" value="{{ $product->expiration_date ?? old('expiration_date') }}"
                        class="mt-1 block w-full rounded-md shadow-sm @error('expiration_date') border-red-500 @else border-gray-300 @enderror">
                        @error('expiration_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Image</label>
                        <input type="file" name="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                        @isset($product->image)
                            <div class="mt-2">
                                <img src="{{ Storage::url($product->image) }}" class="w-16 h-16 rounded object-cover border">
                                <p class="text-xs text-gray-500 mt-1">Leave blank to keep current image.</p>
                            </div>
                        @endisset
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded hover:bg-teal-700">
                    {{ isset($product) ? 'Update' : 'Save' }} Product
                </button>
            </div>
        </form>
    </div>
</x-app-layout>