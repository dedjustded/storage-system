<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }
    public function create()
    {
        return view('products.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'unit_price' => 'required|numeric',
            'availability' => 'required|integer'
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Продуктът беше добавен успешно!');
    }
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string',
            'unit_price' => 'required|numeric',
            'availability' => 'required|integer'
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Продуктът беше актуализиран успешно!');
    }
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Продуктът беше изтрит успешно!');
    }
}