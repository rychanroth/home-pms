<x-app-layout>
    <div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold">Sales History</h2>
            <p class="text-sm text-gray-500 mt-1">Financial summaries of completed transactions.</p>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Receipt #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cashier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-teal-700">
                                {{ $sale->sale_number }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $sale->cashier->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $sale->payment_method->label() }}
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900 text-right">
                                ${{ number_format($sale->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $sale->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <!-- No Edit, No Delete. Just View. -->
                                <a href="{{ route('admin.sales.show', $sale) }}" 
                                class="text-indigo-600 hover:text-indigo-900 inline-flex items-center space-x-1">
                                    <x-heroicon-o-eye class="w-4 h-4" />
                                    <span class="text-sm font-medium">View</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                No sales recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-center">
            {{ $sales->links() }}
        </div>
    </div>
</x-app-layout>