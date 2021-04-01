<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
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
            'title' => $this->title,
            'id' => $this->id,
            'category_id' => $this->category_id,
            'ingredients' => $this->ingredients,
            'image_path' => $this->image_path,
            'price' => $this->price,
        ];
    }
}
