<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <!-- Back Button -->
        <a href="{{ route('admin.sales.index') }}" class="inline-flex items-center space-x-1 text-gray-600 hover:text-gray-900 mb-6">
            <x-heroicon-o-arrow-left class="w-4 h-4" />
            <span>Back to Sales</span>
        </a>

        <!-- Receipt Card -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-8">
            <div class="text-center border-b border-dashed border-gray-300 pb-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Aeterna Pharmacy</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $sale->created_at->format('F d, Y h:i:s A') }}</p>
                <p class="text-lg font-bold text-teal-700 mt-2">{{ $sale->sale_number }}</p>
            </div>

            <div class="flex justify-between text-sm text-gray-600 mb-6">
                <span>Cashier: <strong class="text-gray-900">{{ $sale->cashier->first_name }}</strong></span>
                <span>Payment: <strong class="text-gray-900">{{ $sale->payment_method->label() }}</strong></span>
            </div>

            <table class="w-full mb-6">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">Item</th>
                        <th class="text-center py-2 text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="text-right py-2 text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="text-right py-2 text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                        <tr class="border-b border-gray-100">
                            <td class="py-2 text-gray-800">{{ $item->product->name ?? 'Deleted Product' }}</td>
                            <td class="py-2 text-center text-gray-600">{{ $item->quantity }}</td>
                            <td class="py-2 text-right text-gray-600">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-2 text-right font-medium text-gray-900">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="border-t-2 border-gray-800 pt-4 flex justify-end">
                <div class="w-1/2">
                    <div class="flex justify-between text-lg font-bold text-gray-900">
                        <span>TOTAL:</span>
                        <span>${{ number_format($sale->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>