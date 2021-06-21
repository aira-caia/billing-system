<?php

namespace App\Http\Controllers;

use App\Http\Resources\MenuResource;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // This method is called, when we fetch our menus both in web and mobile app
        $menu = Menu::class;

        // When we use search on mobile app this variable handles the query
        $menu = $menu::where("title", "like", "%{$request->get("s")}%");

        if (!$request->get('query') || $request->get('query') === "All") {
            $menu = $menu->get();
        } else {
            $menu = $menu->where('category_id', $request->get('query'))->get();
        }


        return response(['data' => MenuResource::collection($menu)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //This method is called , when we store a menu on our web application.
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'image_real' => 'required|image|mimes:jpeg,png,jpg,gif',
            'ingredients' => 'nullable|string',
            'category_id' => 'required|numeric|exists:categories,id'
        ]);

        $image = $request->file('image');
        $image_real = $request->file('image_real');
        $imageName = time() . "." . $image->getClientOriginalExtension();
        $image->storeAs("/public/images", $imageName);
        $image_real->storeAs("/public/images/menu", $imageName);
        $validated['image_path'] = $imageName;
        Menu::create($validated);

        return response([
            'message' => "New menu has been created."
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        //This method is called , when we delete a menu

        //If the selected menu has an existing record of purchases, we will not be able to delete that,
        //because of foreign key constraints on our database
        if ($menu->purchases->count() > 0) {
            return response(["message" => "This menu has an existing records of purchases."], 400);
        }

        //Delete the image of selected menu
        Storage::disk('public')->delete("images/" . $menu->image_path);
        Storage::disk('public')->delete("images/menu/" . $menu->image_path);
        $menu->delete();
        return response([
            'message' => "OK"
        ]);
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'ingredients' => 'nullable|string',
        ]);
        $menu->update($validated);

        return response([
            'message' => "New menu has been created."
        ]);
    }
}
