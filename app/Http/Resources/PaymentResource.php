<?php

namespace App\Http\Resources;

use App\Models\Menu;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

// This class is called resource, it formats our Model that we need to send to our client,
//or system
// Format for retrieving payment transactions, they are sent in an array format too.
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
        if ($this->type == "split_item") {
            $payments = Payment::where("order_code", $this->order_code)->orderBy("id")->get();
            $orders = collect();
            $payments->each(function ($p) use (&$orders) {
                $store = PurchaseResource::collection($p->references->first()->purchases);
                $load = collect();
                $store->each(function (&$order) use (&$orders, &$load) {
                    $order['title'] = $order->menu->title;
                    $order['image'] = $order->menu->image_path;
                    $order['ingredients'] = $order->menu->ingredients ?? "Not Specified";
                    if ($orders->contains("menu_id", "=", $order->menu_id)) {
                        $totalCount = intval($orders->firstWhere("menu_id", "=", $order->menu_id)->count) + $order->count;
                        $totalAmount = floatval($orders->firstWhere("menu_id", "=", $order->menu_id)->amount) + $order->amount;
                        $orders->firstWhere("menu_id", "=", $order->menu_id)->count = $totalCount == 1 ? $totalCount . " PIECE" : $totalCount . " PIECES";
                        $orders->firstWhere("menu_id", "=", $order->menu_id)->amount = number_format($totalAmount, 2);
                    } else {
                        $order['count'] = $order->count == 1 ? $order->count . " PIECE" : $order->count . " PIECES";
                        $order['amount'] =  number_format($order->amount, 2);
                        $load = $load->concat($order);
                    }
                });

                if ($load->count() > 0) {
                    $orders = $orders->concat($load->filter(function ($value) {
                        return $value != null;
                    }));
                }
            });
        } else {
            $orders = PurchaseResource::collection($this->references->first()->purchases);
        }

        return [
            "id" => $this->id,
            "paid_at" => Carbon::parse($this->created_at)->isoFormat("lll"),
            "time_passed" => Carbon::parse($this->created_at)->diffInMinutes() . '',
            "total" => number_format($this->where("order_code", $this->order_code)->sum("amount"), 2),
            "orders" => $orders,
            "is_served" => $this->is_served,
            "receipt_number" => $this->receipt_number,
            "reference" => $this->references->first()->reference_number,
            "type" => $this->type,
            "order_code" => $this->order_code,
            "payment_id" => $this->payment_id,
            "table_name" => $this->table_name,
            'total_preparation' => Menu::whereIn('id', $this->references->first()->purchases->pluck('menu_id')->toArray())->sum('preparation_time'),
        ];
    }
}
