<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        // We need products with their images to display the grid.
        // Only get products that are active and have stock!
        $products = Product::where('is_active', true)
                            ->where('stock_quantity', '>', 0)
                            ->get();

        return view('cashier.pos', compact('products'));
    }
}
