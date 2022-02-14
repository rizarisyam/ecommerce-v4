<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = Customer::paginate();
        // $customer = Customer::all();
        return CustomerResource::collection($customer);
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
        // return $request->all();
        $avatarPath = "";
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarPath = $avatar->store('avatars', 'public');
        }
        $customer = Customer::create([
            'user_id' => $request->user_id,
            'username' => $request->username,
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
            'avatar' => $avatarPath
        ]);

        return new CustomerResource($customer);
        // return new CustomerResource($customer);
        // return response()->json('sukses');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);

        return new CustomerResource($customer);
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
        // return $request->all();
        $avatarPath = "";
        $customer = Customer::findOrFail($id);
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarPath = $avatar->store('avatars', 'public');
        } else {
            $avatarPath = $customer->avatar;
        }

        $data = $customer->update([
            'user_id' => $request->user_id,
            'username' => $request->username,
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
            'avatar' => $avatarPath
        ]);
        return response()->json($data, 200);
        // return Redirect::route('customer.index');
        // return new CustomerResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return new CustomerResource($customer);
        // return response()->json('sukses');
    }
}
