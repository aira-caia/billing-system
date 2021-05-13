<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PurchaseResource extends JsonResource
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
            "title" => $this->menu->title,
            "ingredients" => $this->menu->ingredients ?? "Not Specified",
            "count" => $this->count == 1 ? $this->count . " PIECE" : $this->count . " PIECES",
            "amount" => number_format($this->amount, 2),
            "image" => $this->menu->image_path,
        ];
    }
}
