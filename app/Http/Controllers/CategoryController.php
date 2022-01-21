<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Factory;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CategoryResource::collection(Category::all());
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
        // This method is called, when we add a new category on the system (web)
        $validated = $request->validate([
            'title' => 'required|string|unique:categories',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);
        //Store the image
        $image = $request->file('image');
        $imageName = time() . "." . $image->getClientOriginalExtension();

        $factory = (new Factory)->withServiceAccount(__DIR__ . '/config.json');
        $bucket = $factory->createStorage()->getBucket();
        $path = $bucket->upload(file_get_contents($image), ['name' => 'categories/' . $imageName])->signedUrl(new \DateTime('2400-04-15'));


        //        $image->storeAs("/public/images/categories", $imageName);
        $validated['image_path'] = $path;
        Category::create($validated);

        $this->log("Category", "New Category added by " . $request->user()->username);

        return response([
            'message' => "OK"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Category $category)
    {
        // Method is called when we delete the category
        if ($category->menus->count() > 0) {
            return response([
                'message' => "Category has an existing menu."
            ], 400);
        }
        // delete the image of that category
        Storage::disk('public')->delete("images/categories/" . $category->image_path);
        $category->delete();

        $this->log("Category", "Category deleted by " . $request->user()->username);

        return response([
            'message' => "OK"
        ]);
    }
}
