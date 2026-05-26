<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Default to current month if no dates provided
        $startDate = $request->start_date ? now()->parse($request->start_date)->startOfDay() : now()->startOfMonth();
        $endDate = $request->end_date ? now()->parse($request->end_date)->endOfDay() : now()->endOfDay();

        // 1. Sales & Estimated Profit in Period
        $salesInPeriod = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->with('items.product', 'items.stockMovement') // Eager load for profit calc
            ->get();

        $totalRevenue = $salesInPeriod->sum('total_amount');

        $estimatedProfit = 0;
        foreach ($salesInPeriod as $sale) {
            foreach ($sale->items as $item) {
                // Use the cost price recorded at the time of the sale movement
                $costPrice = $item->stockMovement->unit_cost ?? 0;
                $margin = ($item->unit_price - $costPrice) * $item->quantity;
                $estimatedProfit += $margin;
            }
        }

        // 2. Top 5 Most Sold Products (All time for simplicity, can be filtered to period too)
        $topProducts = SaleItem::selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->with('product')
            ->get();


        // 1. Today's Sales Total
        $todaySales = Sale::whereDate('created_at', today())
            ->sum('total_amount');

        // 2. Total Active Products
        $totalProducts = Product::where('is_active', true)->count();

        // 3. Low Stock Count (<= 5 items)
        $lowStockCount = Product::where('stock_quantity', '<=', 5)
            ->where('stock_quantity', '>', 0) // Out of stock is a separate emergency
            ->where('is_active', true)
            ->count();

        // 4. Near Expiry Count (Expiring within 30 days, but not already expired)
        $nearExpiryCount = Product::where('expiration_date', '>', now())
            ->where('expiration_date', '<=', now()->addDays(30))
            ->where('is_active', true)
            ->count();

        // 5. Recent Sales (For the bottom list)
        $recentSales = Sale::with('cashier')
            ->latest()
            ->take(5)
            ->get();

        // 6. Actual Low Stock Items (For the bottom list)
        $lowStockItems = Product::where('stock_quantity', '<=', 5)
            ->where('stock_quantity', '>', 0)
            ->where('is_active', true)
            ->orderBy('stock_quantity', 'asc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'estimatedProfit',
            'startDate',
            'endDate',
            'topProducts',
            'todaySales',
            'totalProducts',
            'lowStockCount',
            'nearExpiryCount',
            'recentSales',
            'lowStockItems'
        ));
    }
}
