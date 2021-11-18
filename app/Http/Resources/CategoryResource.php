<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

// This class is called resource, it formats our Model that we need to send to our client,
//or system
// Format for retrieving categories, they are sent in an array format too.
class CategoryResource extends JsonResource
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
            "value" => $this->id,
            "title" => $this->title,
//            "icon" => env("APP_URL") . Storage::url("images/categories/" . $this->image_path),
            "icon" => $this->image_path,
            "active" => false
        ];
    }
}
