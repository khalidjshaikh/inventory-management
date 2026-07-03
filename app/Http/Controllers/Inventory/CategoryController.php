<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreCategoryRequest;
use App\Http\Requests\Inventory\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(15);
        return view('inventory.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('inventory.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        $products = $category->products()->latest()->paginate(15);
        return view('inventory.categories.show', compact('category', 'products'));
    }

    public function edit(Category $category)
    {
        return view('inventory.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->products()->update(['category_id' => null]);
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
