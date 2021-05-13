<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
