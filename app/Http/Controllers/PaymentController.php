<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    // http://127.0.0.1:8000/payment?status=success&key=$2y$10$tmoxPjspNPUZvXDUsMg.huWw4RGsaA.aiivrKs1kOhafxaMubAAZ.&reference_id=1234567&type=full&amount=1
    public function index(Request $request)
    {
        $validated = $request->validate([
            "status" => "required|in:success,failed",
            "key" => "required|string|min:10",
            "reference_id" => "required|string|min:5",
            "type" => "required|in:full,split",
            "split_count" => "nullable|numeric|min:0",
            "amount" => "required|numeric|min:1",
            "items" => "required|numeric|min:1",
        ]);

        if (!Hash::check('P@$$worD123', $request->key)) abort(404);

        if ($request->status === "success") {
            if ($request->type === "full") {
                $payment = Payment::where("reference_id", $request->reference_id)->get();
                if ($payment->count() > 0) {
                    return view("success");
                } else {
                    Payment::create($validated);
                    return view("success");
                }
            }
        }
    }

    public function store(Request $request)
    {
        if (!$request->bearerToken() ||
            !Hash::check('P@$$worD123', $request->bearerToken()))
            return response(["message" => "Access Forbidden"], 403);

        if ($request->type === "full") {
            $validated = Validator::make($request->all(), [
                "order_code" => "required|string|min:3",
                "amount" => "required|numeric|min:1",
                "type" => "required|in:full,split",
                "split_count" => "nullable|numeric|min:0",
                "table_name" => "required|string",
                "reference_number" => "required|string|min:5",
                "orders" => "required|array"
            ]);
            if ($validated->fails()) {
                return response(['message' => 'Error', 'errors' => $validated->errors()], 400);
            }

            $this->fullPayment($validated->validated());
            return response(["message" => "OK"]);
//            return view("success");
        }


        return response(['message' => "Forbidden Request"],403);
    }

    private function fullPayment($validated)
    {
        $payment = Payment::create($validated);
        $reference = $payment->references()->create($validated);
        $reference->purchases()->createMany($validated['orders']);
    }
}
