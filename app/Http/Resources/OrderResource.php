<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


// This class is called resource, it formats our Model that we need to send to our client,
//or system
// Format for retrieving orders/purchases, they are sent in an array format too.
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "count" => $this->count,
            "title" => $this->menu->title,
            "ingredients" => $this->menu->ingredients,
            "imageName" => $this->menu->imagePath,
        ];
    }
}
