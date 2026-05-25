@use(App\Enums\StockMovementReason)
<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Stock Ledger</h2>
            <a href="{{ route('admin.stock-movements.create') }}" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">+ New Movement</a>
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
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Recorded By</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($movements as $movement)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $movement->created_at->format('M d, h:i A') }}
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                            {{ $movement->product->name }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            @if($movement->reason == StockMovementReason::Sale && $movement->sale)
                            <!-- THE MAGIC BRIDGE -->
                            <a href="{{ route('admin.sales.show', $movement->sale_id) }}"
                                class="text-blue-600 hover:text-blue-800 underline inline-flex items-center space-x-1">
                                <span>Sale</span>
                                <x-heroicon-o-arrow-top-right-on-square class="w-3.5 h-3.5" />
                            </a>
                            @else
                            {{ $movement->reason->label() }}
                            @endif
                        </td>

                        {{-- Color code based on Enum logic --}}
                        <td class="px-4 py-3 text-sm font-semibold whitespace-nowrap
                                @if(in_array($movement->reason, StockMovementReason::inReasons())) 
                                    text-green-600 
                                @else 
                                    text-red-600 
                                @endif">
                            @if(in_array($movement->reason, StockMovementReason::inReasons()))
                            +{{ $movement->quantity }}
                            @else
                            -{{ $movement->quantity }}
                            @endif
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ $movement->supplier->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ $movement->reference ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ $movement->creator->name ?? 'System' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                            No stock movements recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        <div class="mt-4 flex justify-center">
            {{ $movements->links() }}
        </div>
    </div>
</x-app-layout>