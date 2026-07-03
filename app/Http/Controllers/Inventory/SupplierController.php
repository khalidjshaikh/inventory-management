<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreSupplierRequest;
use App\Http\Requests\Inventory\UpdateSupplierRequest;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('products', 'purchaseOrders')->latest()->paginate(15);
        return view('inventory.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('inventory.suppliers.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        Supplier::create($request->validated());

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        return view('inventory.suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('inventory.suppliers.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->products()->update(['supplier_id' => null]);
        $supplier->purchaseOrders()->delete();
        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}
