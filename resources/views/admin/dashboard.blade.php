<x-app-layout>
    
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Here's what's happening with your pharmacy today.</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Today's Sales -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-teal-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Today's Sales</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">${{ number_format($todaySales, 2) }}</p>
                </div>
                <div class="p-3 bg-teal-50 rounded-full">
                    <x-heroicon-o-banknotes class="w-6 h-6 text-teal-600" />
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Products</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalProducts }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-full">
                    <x-heroicon-o-beaker class="w-6 h-6 text-blue-600" />
                </div>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Low Stock Items</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $lowStockCount }}</p>
                </div>
                <div class="p-3 bg-orange-50 rounded-full">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-orange-600" />
                </div>
            </div>
        </div>

        <!-- Near Expiry -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Near Expiry (30d)</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $nearExpiryCount }}</p>
                </div>
                <div class="p-3 bg-red-50 rounded-full">
                    <x-heroicon-o-clock class="w-6 h-6 text-red-600" />
                </div>
            </div>
        </div>
    </div>

    <!-- Lists Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Recent Sales List -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Recent Sales</h2>
            <div class="space-y-3">
                @forelse($recentSales as $sale)
                    <a href="{{ route('admin.sales.show', $sale) }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div>
                            <p class="text-sm font-medium text-teal-700">{{ $sale->sale_number }}</p>
                            <p class="text-xs text-gray-500">{{ $sale->cashier->first_name }} - {{ $sale->created_at->format('h:i A') }}</p>
                        </div>
                        <span class="text-sm font-bold text-gray-800">${{ number_format($sale->total_amount, 2) }}</span>
                    </a>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">No sales today yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Low Stock Alerts List -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Low Stock Alerts</h2>
            <div class="space-y-3">
                @forelse($lowStockItems as $item)
                    <div class="flex items-center justify-between p-3 bg-orange-50 border border-orange-100 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $item->name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->category->name ?? 'Uncategorized' }}</p>
                        </div>
                        <span class="text-sm font-bold text-orange-600">{{ $item->stock_quantity }} left</span>
                    </div>
                @empty
                    <div class="flex items-center justify-center py-4 text-green-600">
                        <x-heroicon-o-check-circle class="w-5 h-5 mr-2" />
                        <span class="text-sm font-medium">All stock levels healthy!</span>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>