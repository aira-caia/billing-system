<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class WebPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $orders = PurchaseResource::collection($this->references->first()->purchases);
        if ($this->type !== "split_item") {
            $total = number_format($this->where("order_code", $this->order_code)->sum("amount"), 2);
        } else {
            $total = number_format($this->amount, 2);
        }
        return [
            "id" => $this->id,
            "paid_at" => Carbon::parse($this->created_at)->isoFormat("lll"),
            "time_passed" => Carbon::parse($this->created_at)->diffForHumans(),
            "total" => $total,
            "orders" => $orders,
            "is_served" => $this->is_served,
            "receipt_number" => $this->receipt_number,
            "reference" => $this->references->first()->reference_number,
            "type" => $this->type,
            "order_code" => $this->order_code,
            "payment_id" => $this->payment_id,
            "table_name" => $this->table_name
        ];
    }
}
