<?php

namespace App\Http\Controllers;

use App\Http\Resources\MenuResource;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Kreait\Firebase\Factory;

class MenuController extends Controller
{

    public function notify()
    {
        $menus = Menu::where('quantity', "<", 21)->get();
        return response($menus);
    }

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
        $menu = $menu::where("title", "like", "%{$request->get("s")}%")->where('status', 1);

        if (!$request->get('query') || $request->get('query') === "All") {
            $menu = $menu->get();
        } else {
            $menu = $menu->where('category_id', $request->get('query'))->get();
        }


        return response(['data' => MenuResource::collection($menu)]);
    }

    public function show(Menu $menu)
    {
        return response(MenuResource::make($menu));
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
            'category_id' => 'required|numeric|exists:categories,id',
            'preparation_time' => 'required|integer',
        ]);


        $image = $request->file('image');
        //        $image_real = $request->file('image_real');
        $imageName = time() . "." . $image->getClientOriginalExtension();

        $factory = (new Factory)->withServiceAccount(__DIR__ . '/config.json');
        $bucket = $factory->createStorage()->getBucket();
        $path = $bucket->upload(file_get_contents($request->file('image_real')), ['name' => 'menu/' . $imageName])->signedUrl(new \DateTime('2400-04-15'));
        $cropped = $bucket->upload(file_get_contents($request->file('image')), ['name' => 'menu/crop/' . $imageName])->signedUrl(new \DateTime('2400-04-15'));

        //        $image->storeAs("/public/images", $imageName);
        //        $image_real->storeAs("/public/images/menu", $imageName);
        $validated['image_path'] = $path;
        $validated['crop_path'] = $cropped;
        Menu::create($validated);

        $this->log("Menu", "New Menu added by " . $request->user()->username);

        return response([
            'message' => "New menu has been created."
        ]);
    }

    public function inventory(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|numeric|exists:menus,id',
            'quantity' => 'required|numeric',
            'remarks' => 'nullable'
        ]);

        $menu = Menu::find($validated['menu_id']);
        $absoluteQuantity = abs(abs($validated['quantity']));
        if ((int) $validated['quantity'] < 0) {
            $this->log("Inventory Decrease", $validated['remarks'] . " - " . $request->user()->username . " ($absoluteQuantity pc/s " . $menu->title . ")");
        } else {
            $this->log("Inventory Increase", $validated['remarks'] . " - " . $request->user()->username . " ($absoluteQuantity pc/s " . $menu->title . ")");
        }

        $menu->increment('quantity', $validated['quantity']);

        return response(['message' => 'Stocks has been updated!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Menu $menu)
    {
        //This method is called , when we delete a menu

        //If the selected menu has an existing record of purchases, we will not be able to delete that,
        //because of foreign key constraints on our database
        if ($menu->purchases->count() > 0) {
            // return response(["message" => "This menu has an existing records of purchases."], 400);
            $menu->update(['status' => 0]);
        } else {
            $menu->delete();
            //Delete the image of selected menu
            // Storage::disk('public')->delete("images/" . $menu->image_path);
            // Storage::disk('public')->delete("images/menu/" . $menu->image_path);
        }

        $this->log("Menu", "Menu deleted by " . $request->user()->username);

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
            'preparation_time' => 'required|integer',
        ]);

        if ((float) $menu->price !== (float) $validated['price']) {
            $validated['previous_price'] = $menu->price;
        }

        $menu->update($validated);

        $this->log("Menu", "Menu updated by " . $request->user()->username);

        return response([
            'message' => "New menu has been created."
        ]);
    }
}
