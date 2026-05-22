<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\ProductType;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager loading!!!
        $products = Product::with(['category', 'productType', 'suppliers'])->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productTypes = ProductType::all();
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('admin.products.form', compact('productTypes', 'categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_type_id' => 'nullable|exists:product_types,id',
            'category_id' => 'nullable|exists:categories,id',
            'base_unit' => 'required|string|max:50',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'expiration_date' => 'nullable|date',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'suppliers' => 'nullable|array',
            'suppliers.*' => 'exists:suppliers,id', // Validate every ID in the array is real
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // 1. Create the product
        $product = Product::create($validated);

        // 2. Attach suppliers to the pivot table!
        if (!empty($validated['suppliers'])) {
            $product->suppliers()->sync($validated['suppliers']);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // EAGER LOADING!!!!
        $product->load('suppliers');

        $productTypes = ProductType::all();
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('admin.products.form', compact('product', 'productTypes', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_type_id' => 'nullable|exists:product_types,id',
            'category_id' => 'nullable|exists:categories,id',
            'base_unit' => 'required|string|max:50',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'expiration_date' => 'nullable|date',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'suppliers' => 'nullable|array',
            'suppliers.*' => 'exists:suppliers,id',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            $validated['image'] = $product->image;
        }

        $product->update($validated);

        // Keep the pivot table in sync!
        $product->suppliers()->sync($validated['suppliers'] ?? []);

        return redirect()->route('admin.products.index')->with('success', 'Product updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted!');
    }
}
