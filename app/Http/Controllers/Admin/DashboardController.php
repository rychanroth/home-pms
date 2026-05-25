<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
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
            'todaySales',
            'totalProducts',
            'lowStockCount',
            'nearExpiryCount',
            'recentSales',
            'lowStockItems'
        ));
    }
}
