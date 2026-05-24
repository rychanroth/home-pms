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
            'product_type_id' => 'nullable|exists:product_types,id',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        // SERVER-SIDE RULE 1: Child must match Parent's Product Type
        if (!empty($validated['parent_id']) && !empty($validated['product_type_id'])) {
            $parent = Category::find($validated['parent_id']);
            if ($parent->product_type_id != $validated['product_type_id']) {
                return back()
                    ->withErrors(['parent_id' => 'The selected parent category belongs to a different Product Type.'])
                    ->withInput();
            }
        }

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

        // Prevent infinite circular reference
        if (!empty($validated['parent_id'])) {
            // 1. Grab the category the user is trying to assign as a parent
            $proposedParent = Category::find($validated['parent_id']);

            // 2. Ask the PROPOSED PARENT: "Are you a descendant of the category I'm currently editing?"
            if ($proposedParent->isDescendantOf($category->id)) {
                return back()
                    ->withErrors(['parent_id' => 'Circular reference detected! You cannot assign a child category as a parent.'])
                    ->withInput();
            }
        }

        // SERVER-SIDE RULE 1: Child must match Parent's Product Type
            if (!empty($validated['product_type_id']) && $proposedParent->product_type_id != $validated['product_type_id']) {
                return back()
                    ->withErrors(['parent_id' => 'The selected parent category belongs to a different Product Type.'])
                    ->withInput();
            }

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
