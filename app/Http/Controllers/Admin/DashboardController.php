<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Carbon; // Import Carbon
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Cleaner Date Parsing
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : now()->startOfMonth();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();

        // 2. Dashboard Aggregates (Done at the DB level, not in RAM)
        $salesInPeriod = Sale::whereBetween('created_at', [$startDate, $endDate]);
        $totalRevenue = (clone $salesInPeriod)->sum('total_amount');
        
        // NOTE: Ideally, calculate this via a SUM() query if you add unit_cost to sale_items.
        // For now, if you must use the loop, use chunk() to prevent server crashes.
        $estimatedProfit = 0;
        (clone $salesInPeriod)->with('items.stockMovement')->chunk(100, function ($sales) use (&$estimatedProfit) {
            foreach ($sales as $sale) {
                foreach ($sale->items as $item) {
                    $costPrice = $item->stockMovement->unit_cost ?? 0;
                    $estimatedProfit += ($item->unit_price - $costPrice) * $item->quantity;
                }
            }
        });

        // 3. Top Products (Now respects the date filter!)
        $topProducts = SaleItem::selectRaw('product_id, SUM(quantity) as total_sold')
            ->whereHas('sale', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->with('product')
            ->get();

        // 4. Quick Stats
        $todaySales = Sale::whereDate('created_at', today())->sum('total_amount');
        $totalProducts = Product::where('is_active', true)->count();
        $nearExpiryCount = Product::whereBetween('expiration_date', [now(), now()->addDays(30)])
            ->where('is_active', true)
            ->count();

        // 5. DRY Low Stock Logic (Write query once, use twice)
        $lowStockQuery = Product::where('stock_quantity', '<=', 5)
            ->where('stock_quantity', '>=', 0)
            ->where('is_active', true);
            
        $lowStockCount = (clone $lowStockQuery)->count();
        $lowStockItems = (clone $lowStockQuery)->orderBy('stock_quantity', 'asc')->take(10)->get();

        // 6. Recent Sales
        $recentSales = Sale::with('cashier')->latest()->take(5)->get();

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