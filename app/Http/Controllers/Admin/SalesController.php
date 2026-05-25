<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sale;
use App\Http\Controllers\Controller;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sale::with('cashier')->latest()->paginate(20);
        return view('admin.sales.index', compact('sales'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['items.product', 'cashier']);
        return view('admin.sales.show', compact('sale'));
    }
}