<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            "id" => $this->id,
            "paid_at" => Carbon::parse($this->created_at)->isoFormat("lll"),
            "time_passed" => Carbon::parse($this->created_at)->diffForHumans(),
            "total" => number_format($this->where("order_code", $this->order_code)->sum("amount"), 2),
            "orders" => PurchaseResource::collection($this->references->first()->purchases),
            "is_served" => $this->is_served
        ];
    }
}
