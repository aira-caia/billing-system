<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

// This class is called resource, it formats our Model that we need to send to our client,
//or system
// Format for retrieving menus, they are sent in an array format too.
class MenuResource extends JsonResource
{

    private static $bests = [];
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'id' => $this->id,
            'category_id' => $this->category_id,
            'ingredients' => $this->ingredients,
            'image_path' =>  $this->image_path,
            'crop_path' =>  $this->crop_path,
            'price' => $this->price,
            'previous_price' => $this->previous_price,
            'preparation_time' => $this->preparation_time,
            'quantity' => $this->quantity,
            'is_available' => $this->is_available,
            'purchases' => $this->purchases->sum('count'),
            'best_seller' => in_array($this->id, self::$bests)
        ];
    }

    public static function customCollection($qry, $bestSellers)
    {
        self::$bests = $bestSellers;
        return static::collection($qry);
    }
}
