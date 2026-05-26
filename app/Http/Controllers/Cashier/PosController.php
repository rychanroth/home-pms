<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        // Get all products, even the inactive ones, so the cashier can see the disabled products.
        $products = Product::all();

        return view('cashier.pos', compact('products'));
    }

    /**
     * Return sales history of the current logged in cashier.
     */
    public function getMySales()
    {
        // Only get sales for the currently logged-in cashier, ordered by newest
        $sales = Sale::where('cashier_id', auth()->id())
            ->latest()
            ->take(15)
            ->get();

        return response()->json($sales);
    }
}
