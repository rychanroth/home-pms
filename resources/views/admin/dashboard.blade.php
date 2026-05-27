<x-app-layout>

    <!-- Page Header -->
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Here's what's happening with your pharmacy today.</p>
    </div>

    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-end space-x-4 mb-4 bg-white p-4 rounded-lg shadow-sm border">
        <div>
            <label class="block text-xs font-medium text-gray-500">Start Date</label>
            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="rounded-md border-gray-300 shadow-sm text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500">End Date</label>
            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="rounded-md border-gray-300 shadow-sm text-sm">
        </div>
        <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-teal-700">Apply</button>
        <a href="{{ route('admin.dashboard') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-300">Reset</a>
    </form>

    <div class="w-full h-1 mt-2 mb-4 bg-gray-200"></div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4 items-stretch">

        <div class="flex flex-col gap-4">
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-emerald-500 flex-1 flex flex-col justify-center">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Est. Profit</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">${{ number_format($estimatedProfit, 2) }}</p>
                        <p class="text-xs text-gray-400 mt-1">*Based on last known cost</p>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-full"><x-heroicon-o-arrow-trending-up class="w-6 h-6 text-emerald-600" /></div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-teal-500 flex-1 flex flex-col justify-center">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">${{ number_format($totalRevenue, 2) }}</p>
                    </div>
                    <div class="p-3 bg-teal-50 rounded-full"><x-heroicon-o-banknotes class="w-6 h-6 text-teal-600" /></div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-2 flex flex-col h-full">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Top 5 Best Sellers</h2>
            <div class="space-y-3 flex-1">
                @forelse($topProducts as $index => $topItem)
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-bold text-blue-600 w-6">#{{ $index + 1 }}</span>
                        <span class="text-sm font-medium text-gray-800">{{ $topItem->product->name }}</span>
                    </div>
                    <span class="text-sm font-bold text-blue-800">{{ $topItem->total_sold }} sold</span>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">No sales data yet.</p>
                @endforelse
            </div>
        </div>

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