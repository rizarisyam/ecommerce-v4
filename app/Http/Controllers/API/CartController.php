<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CartController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return $request->user_id;
        $user = User::findOrFail($request->user_id);
        return $user->products()->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $product = $user->products()->where('product_id', $request->product_id)->first();

        // return $product;

        if ($product) {
            $quantity = $product->pivot->quantity;

            $user->products()->updateExistingPivot($request->product_id, [
                'quantity' => $request->quantity,
            ]);
        } else {
            $user->products()->attach($request->product_id, [
                'quantity' => $request->quantity,
                'price' => $request->price
            ]);
        }



        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($userId)
    {
        $user = User::findOrFail($userId);
        $user->products()->detach();
        return response()->json([
            'message' => 'Cart dengan ' . $user->name . "berhasil dihapus"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteByProduct(Request $request, $id)
    {
        // return $id;
        // return $request->query('user_id');
        $user = User::findOrFail($id);
        // return $user;
        $product = Product::findOrFail($request->query('product_id'));
        // return $product;
        $user->products()->detach($product);
        return response()->json([
            'message' => 'cart berhasil dihapus'
        ]);
    }

    function invoiceNumber()
    {
        $latest = Order::latest()->first();
        if (!$latest) {
            return "F-" . date("ymd") . str_pad(1, 3, "0", STR_PAD_LEFT);
        }

        $string = preg_replace("/[^0-9\.]/", '', $latest->invoice_number);

        return 'F-' . sprintf('%04d', $string + 1);
    }

    public function checkout(Request $request)
    {

        $order = Order::create([
            'user_id' => $request->user_id,
            'invoice_number' => $this->invoiceNumber(),
        ]);


        $product = Product::findMany($request->product_id);

        $checkout = $order->products()->attach($product, [
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);

        return Redirect::route('carts.shipment', $request->user_id);
    }

    public function shipment(Request $request)
    {
        $query = $request->query('user_id');
        return OrderResource::collection(Order::where('user_id', $query)->get());
    }
}
