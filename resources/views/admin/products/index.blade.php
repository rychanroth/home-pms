<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Products</h2>
            <a href="{{ route('admin.products.create') }}" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">+ Add New Product</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expires</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50">
                            <!-- Image -->
                            <td class="px-6 py-4">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" class="w-10 h-10 rounded object-cover">
                                @else
                                    <span class="text-gray-400 text-sm">No img</span>
                                @endif
                            </td>

                            <!-- Name & Suppliers -->
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $product->suppliers->count() }} Supplier(s)
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $product->category->name ?? '-' }}
                            </td>

                            <!-- Type -->
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $product->productType->name ?? '-' }}
                            </td>

                            <!-- Price -->
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                ${{ number_format($product->selling_price, 2) }}
                            </td>

                            <!-- Stock with Low Stock Warning -->
                            <td class="px-6 py-4 text-sm">
                                @if($product->stock_quantity <= 5 && $product->stock_quantity > 0)
                                    <span class="font-semibold text-orange-600">{{ $product->stock_quantity }} {{ $product->base_unit }}s</span>
                                @elseif($product->stock_quantity == 0)
                                    <span class="font-semibold text-red-600">Out of Stock</span>
                                @else
                                    <span class="text-gray-900">{{ $product->stock_quantity }} {{ $product->base_unit }}s</span>
                                @endif
                            </td>

                            <!-- Expiration with Expired Warning -->
                            <td class="px-6 py-4 text-sm">
                                @if($product->expiration_date && $product->expiration_date->isPast())
                                    <span class="font-semibold text-red-600">
                                        {{ $product->expiration_date->format('M d, Y') }} (Expired)
                                    </span>
                                @elseif($product->expiration_date)
                                    <span class="text-gray-700">
                                        {{ $product->expiration_date->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.products.edit', $product) }}" 
                                class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">Edit</a>
                                
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-sm" 
                                            onclick="return confirm('Are you sure you want to delete this product?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                No products found. Start by adding one!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>