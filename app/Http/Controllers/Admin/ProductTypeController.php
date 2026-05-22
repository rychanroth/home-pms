<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productTypes = ProductType::all();
        return view('admin.product-types.index', compact('productTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.product-types.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requires_expiration' => 'boolean',
            'image' => 'nullable|image|max:2048', // max 2MB
        ]);

        // HANDLE THE IMAGE
        if ($request->hasFile('image')) {
            // Store in storage/app/public/product_types folder
            $path = $request->file('image')->store('product_types', 'public');
            $validated['image'] = $path; // Save the path to database
        }

        ProductType::create($validated);
        return redirect()->route('admin.product-types.index')->with('success', 'Product Type created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductType $productType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductType $productType)
    {
        return view('admin.product-types.form', compact('productType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductType $productType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requires_expiration' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('product_types', 'public');
            $validated['image'] = $path;
        } else {
            $validated['image'] = $productType->image;
        }

        $productType->update($validated);

        return redirect()->route('admin.product-types.index')->with('success', 'Product Type updated!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductType $productType)
    {
        // 1. Delete the physical file from the server
        if ($productType->image) {
            Storage::disk('public')->delete($productType->image);
        }

        // 2. Delete the database row
        $productType->delete();

        return redirect()->route('admin.product-types.index')->with('success', 'Product Type deleted!');
    }
}
