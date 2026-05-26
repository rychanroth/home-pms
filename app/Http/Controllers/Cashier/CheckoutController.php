<?php

namespace App\Http\Controllers\Cashier;

use App\Enums\StockMovementReason;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Log;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        // THE TRANSACTION WALL: If anything inside this closure fails, EVERYTHING rolls back.
        try {
            $sale = DB::transaction(function () use ($cart, $request) {

                // --- THE REALITY CHECK WALL ---
                // Checked inside the transaction so database rows can be evaluated atomically
                foreach ($cart as $productId => $item) {
                    $product = Product::find($productId);

                    if (!$product) {
                        throw new \InvalidArgumentException("Transaction failed: Product no longer exists.");
                    }

                    if (!$product->is_active) {
                        throw new \InvalidArgumentException("Transaction failed: '{$product->name}' is deactivated.");
                    }

                    if ($product->expiration_date && $product->expiration_date->isPast()) {
                        throw new \InvalidArgumentException("Transaction failed: '{$product->name}' is expired.");
                    }

                    if ($product->stock_quantity < $item['qty']) {
                        throw new \InvalidArgumentException("Transaction failed: Not enough stock for '{$product->name}'. Only {$product->stock_quantity} left.");
                    }
                }

                // 1. Generate Sale Number (e.g., POS-20250122-AB12)
                $saleNumber = 'POS-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

                // 2. Create the Sale Record
                $sale = Sale::create([
                    'sale_number' => $saleNumber,
                    'cashier_id' => auth()->id(),
                    'total_amount' => 0, // We will calculate this below
                    'payment_method' => $request->payment_method ?? 'cash',
                ]);

                $totalAmount = 0;

                // 3. Loop through cart and create Sale Items & Stock Movements
                foreach ($cart as $productId => $item) {
                    $subtotal = $item['price'] * $item['qty'];
                    $totalAmount += $subtotal;

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $productId,
                        'quantity' => $item['qty'],
                        'unit_price' => $item['price'],
                        'subtotal' => $subtotal,
                    ]);

                    // Write to the Central Immutable Ledger
                    StockMovement::create([
                        'product_id' => $productId,
                        'reason' => StockMovementReason::Sale,
                        'sale_id' => $sale->id,
                        'quantity' => $item['qty'],
                        'created_by_id' => auth()->id(),
                    ]);

                    // Decrement physical stock
                    Product::where('id', $productId)->decrement('stock_quantity', $item['qty']);
                }

                // Update the sale with the final calculated total
                $sale->update(['total_amount' => $totalAmount]);

                return $sale;
            });

            // 4. If transaction succeeds, wipe the session cart
            session()->forget('cart');

            return response()->json([
                'success' => true,
                'message' => 'Sale completed!',
                'sale_number' => $sale->sale_number,
                'total' => $sale->total_amount
            ]);
        } catch (\InvalidArgumentException $e) {
            // Catching intentional business logic failures
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            // Catching unexpected explosions (e.g., Oracle DB drops offline, deadlock, missing column)
            // Log the real issue for debugging, but keep the user facing message secure.
            Log::error('System Checkout Failure: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Checkout failed. Please try again.'], 500);
        }
    }
}
