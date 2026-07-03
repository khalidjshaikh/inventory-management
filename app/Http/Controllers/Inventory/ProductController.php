<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreProductRequest;
use App\Http\Requests\Inventory\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'supplier'])
            ->withCount('stockHistories')
            ->latest()
            ->paginate(15);

        return view('inventory.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        return view('inventory.products.create', compact('categories', 'suppliers'));
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'supplier', 'stockHistories' => fn($q) => $q->latest()->take(20)]);
        return view('inventory.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        return view('inventory.products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function showBarcode(Product $product)
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($product->barcode, $generator::TYPE_CODE_128);

        return response($barcode, 200, [
            'Content-Type' => 'image/png',
        ]);
    }
}
