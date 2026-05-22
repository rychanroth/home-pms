<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductType;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with(['parent', 'productType'])->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Pass data for the dropdowns!
        $productTypes = ProductType::all();
        $allCategories = Category::all();
        
        return view('admin.categories.form', compact('productTypes', 'allCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_type_id' => 'nullable|exists:product_types,id', // Must be a real ID!
            'parent_id' => 'nullable|exists:categories,id',         // Must be a real ID!
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $productTypes = ProductType::all();
        // THE WALL PREVENTION: Exclude the current category from the parent dropdown list
        $allCategories = Category::where('id', '!=', $category->id)->get();

        return view('admin.categories.form', compact('category', 'productTypes', 'allCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_type_id' => 'nullable|exists:product_types,id',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Optional: Delete old image if replacing it
            if ($category->image) Storage::disk('public')->delete($category->image);
            $validated['image'] = $request->file('image')->store('categories', 'public');
        } else {
            $validated['image'] = $category->image;
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->image) Storage::disk('public')->delete($category->image);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted!');
    }
}
