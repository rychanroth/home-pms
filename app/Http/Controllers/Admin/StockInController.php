<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StockMovementReason;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Ironically, StockMovement does not have a dedicated controller.
 * `StockInController` is a variant, and the only choice that could be done with StockMovement.
 * For StockOut, we'll implement it on `SaleController`.
 */
class StockInController extends Controller
{
    public function index()
    {
        // Eager load relationships to prevent N+1 queries
        $movements = StockMovement::with(['product', 'supplier', 'creator'])->latest()->paginate(20);
        return view('admin.stock-movements.index', compact('movements'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('admin.stock-movements.form', compact('products', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'reason' => ['required', Rule::enum(StockMovementReason::class)],
            'supplier_id' => 'nullable|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'nullable|numeric|min:0',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $reasonEnum = StockMovementReason::from($validated['reason']);

        // Use a database transaction to secure atomicity
        return DB::transaction(function () use ($validated, $reasonEnum) {

            // 1. Lock the product row for editing to block other incoming concurrent requests
            $product = Product::lockForUpdate()->find($validated['product_id']);

            // 2. Prevent Negative Stock Wall
            if (in_array($reasonEnum, StockMovementReason::outReasons())) {
                if ($product->stock_quantity < $validated['quantity']) {
                    return back()
                        ->withErrors(['quantity' => "Not enough stock! You only have {$product->stock_quantity} available."])
                        ->withInput();
                }
            }

            // 3. Write to the Immutable Ledger
            StockMovement::create([
                'product_id' => $validated['product_id'],
                'reason' => $reasonEnum,
                'supplier_id' => $validated['supplier_id'],
                'quantity' => $validated['quantity'],
                'unit_cost' => $validated['unit_cost'],
                'reference' => $validated['reference'],
                'notes' => $validated['notes'],
                'created_by_id' => auth()->id(),
            ]);

            // 4. Update the product balance atomically
            if (in_array($reasonEnum, StockMovementReason::inReasons())) {
                $product->increment('stock_quantity', $validated['quantity']);
            } else {
                $product->decrement('stock_quantity', $validated['quantity']);
            }

            return redirect()->route('admin.stock-movements.index')->with('success', 'Stock movement recorded successfully!');
        });
    }

    // Our controller does not handle method like update/delete because our rule strictly prohibit it!
}
