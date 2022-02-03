<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $categories = CategoryResource::collection(Category::all());
        return Inertia::render('Category/Index', [
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Category/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $files = $request->file('fileUpload');

        // return json_encode($files);
        $path_push = [];

        foreach($files as $file) {
            $path = $file->store('fileUpload', 'public');
            array_push($path_push, $path);
        }
        // $path = $files[0]->store('fileUpload');

        $category = Category::create([
            'name' => $request->name,
            'desc' => $request->desc,
            'image_path' => json_encode($path_push)
        ]);

        return Redirect::route('categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);

        return Inertia::render("Category/Show", [
            'category' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return Inertia::render('Category/Edit', [
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        if($request->hasFile('fileUpload')) {
            $files = $request->file('fileUpload');
            $path_push = [];
            foreach($files as $file) {
                $path = $file->store('fileUpload', 'public');
                array_push($path_push, $path);
            }
            $imagePath =  $path_push;
        } else {
            $imagePath = json_decode($category->image_path);
        }

        $data = $category->update([
            'name' => $request->name,
            'desc' => $request->desc,
            'image_path' => json_encode($imagePath)
        ]);

        if($data) {
            return Redirect::route('categories.index');
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
