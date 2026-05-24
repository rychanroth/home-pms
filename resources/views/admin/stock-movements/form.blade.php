@use(App\Enums\StockMovementReason)
<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <h2 class="text-2xl font-bold mb-6">Record Stock Movement</h2>
        
        <form method="POST" action="{{ route('admin.stock-movements.store') }}">
            @csrf

            <div class="space-y-4">
                <!-- Product Dropdown -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Product</label>
                    <select name="product_id" class="mt-1 block w-full rounded-md shadow-sm @error('product_id') border-red-500 @else border-gray-300 @enderror" required>
                        <option value="">-- Select Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} (Stock: {{ $product->stock_quantity }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- ENUM DROPDOWN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Reason</label>
                    <select name="reason" id="reason_select" class="mt-1 block w-full rounded-md shadow-sm @error('reason') border-red-500 @else border-gray-300 @enderror" required>
                        <option value="">-- Select Reason --</option>
                        @foreach(StockMovementReason::options() as $value => $label)
                            <option value="{{ $value }}" {{ old('reason') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('reason') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Supplier Dropdown -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Supplier (Optional)</label>
                    <select name="supplier_id" class="mt-1 block w-full rounded-md shadow-sm @error('supplier_id') border-red-500 @else border-gray-300 @enderror">
                        <option value="">-- None --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Quantity & Unit Cost -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="quantity" value="{{ old('quantity') }}" min="1"
                        class="mt-1 block w-full rounded-md shadow-sm @error('quantity') border-red-500 @else border-gray-300 @enderror" required>
                        @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit Cost ($)</label>
                        <input type="number" name="unit_cost" step="0.01" value="{{ old('unit_cost') }}" min="0"
                        class="mt-1 block w-full rounded-md shadow-sm @error('unit_cost') border-red-500 @else border-gray-300 @enderror">
                        @error('unit_cost') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Reference & Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Reference / Invoice #</label>
                    <input type="text" name="reference" value="{{ old('reference') }}"
                    class="mt-1 block w-full rounded-md shadow-sm @error('reference') border-red-500 @else border-gray-300 @enderror">
                    @error('reference') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" rows="3"
                    class="mt-1 block w-full rounded-md shadow-sm @error('notes') border-red-500 @else border-gray-300 @enderror"
                    placeholder="Any additional details...">{{ old('notes') }}</textarea>
                    @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded hover:bg-teal-700">
                    Record Movement
                </button>
            </div>
        </form>
    </div>
</x-app-layout>