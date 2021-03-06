<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CategoryResource::collection(Category::latest()->get());
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
        return $request->all();
        // return $request->all();
        $files = $request->file('fileUpload');

        // return json_encode($files);
        $path_push = [];

        if ($request->hasFile('fileUpload')) {
            foreach ($files as $file) {
                $path = $file->store('fileUpload', 'public');
                array_push($path_push, $path);
            }
        }
        // $path = $files[0]->store('fileUpload');

        $category = Category::create([
            'name' => $request->name,
            'desc' => $request->desc,
            'image_path' => json_encode($path_push)
        ]);

        // return Redirect::route('categories.index');
        return $category;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return new CategoryResource($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return new CategoryResource($category);
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
        $files = $request->file('fileUpload');
        $category = Category::findOrFail($id);
        // return json_encode($files);
        $path_push = [];

        if ($request->hasFile('fileUpload')) {
            foreach ($files as $file) {
                $path = $file->store('fileUpload', 'public');
                array_push($path_push, $path);
            }
        } else {
            $path_push = $category->image_path;
        }
        // $path = $files[0]->store('fileUpload');

        $category->update([
            'name' => $request->input('name'),
            'desc' => $request->input('desc'),
            'image_path' => $path_push
        ]);

        return Redirect::route('categories.index');
        // return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return new CategoryResource($category);
    }
}
