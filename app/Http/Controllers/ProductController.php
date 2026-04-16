<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('product', compact('products'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'product_name' => $request->product_name,
            'price' => $request->price,
            'deposit' => $request->deposit,
        ];

        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/products');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $imageName);
            $data['product_image'] = $imageName;
        }

        Product::create($data);

        return redirect()->route('product.index')->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        return view('show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'product_name' => $request->product_name,
            'price' => $request->price,
            'deposit' => $request->deposit,
        ];

        if ($request->hasFile('product_image')) {
            if ($product->product_image) {
                Storage::delete('public/products/' . $product->product_image);
            }

            $image = $request->file('product_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/products', $imageName);
            $data['product_image'] = $imageName;
        }

        $product->update($data);

        return redirect()->route('product.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->product_image) {
            Storage::delete('public/products/' . $product->product_image);
        }

        $product->delete();

        return redirect()->route('product.index')->with('success', 'Product deleted successfully!');
    }
}