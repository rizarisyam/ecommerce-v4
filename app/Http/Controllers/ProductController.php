<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Product/Index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return Inertia::render('Product/Create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $images = array();
            foreach ($request->file('image') as $row) {
                $image = $row->store('products', 'public');
                $images[] = $image;
            }
            // $image = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'desc' => $request->desc,
            'price' => $request->price,
            'quantity' => $request->input('quantity'),
            'discount' => $request->discount,
            'SKU' => $request->SKU,
            'category_id' => $request->category_id,
            'image_path' => json_encode($images)
        ]);

        return Redirect::route('products.index')->with('message', 'Data created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return Inertia::render('Product/Edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $image = $product->image_path;

        if ($request->hasFile('image')) {
            if (Storage::exists('storage/' . $product->image_path)) {
                Storage::delete('public/' . $product->image_path);
            }
            $image = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'desc' => $request->desc,
            'price' => $request->price,
            'quantity' => $request->input('quantity'),
            'discount' => $request->discount,
            'SKU' => $request->SKU,
            'category_id' => $request->category_id,
            'image_path' => $image
        ]);

        return Redirect::route('products.index')->with('message', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        Storage::delete('public/' . $product->image_path);
        $product->delete();

        return Redirect::route('products.index')->with('message', 'Data deleted successfully');
    }
}
