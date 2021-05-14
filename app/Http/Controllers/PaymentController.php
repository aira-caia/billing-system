<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\PurchaseResource;
use App\Models\Payment;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{


    public function orders()
    {
        $payments = PaymentResource::collection(Payment::orderByDesc("id",)->groupBy("order_code")->get());
        return $payments;
    }

    public function show(Request $request, $orderCode)
    {
        if (
            !$request->bearerToken() ||
            !Hash::check('P@$$worD123', $request->bearerToken())
        )
            return response(["message" => "Access Forbidden"], 403);

        $payment = Payment::where("order_code", $orderCode)->orderBy("id")->first();
        $orders = PurchaseResource::collection($payment->references->first()->purchases);

        if ($payment->type === "full") $paymentType = "FULL PAYMENT";
        elseif ($payment->type === "split_equally") $paymentType = "SPLIT EQUALLY";
        else $paymentType = "SPLIT BY ITEM";

        $orderCode = $payment->order_code;
        $numberOfAccounts = Payment::where("order_code", $orderCode)->count();
        $receiptNumbers = Payment::where("order_code", $orderCode)->pluck("receipt_number");
        $refNumber = $payment->references->first()->reference_number;
        $total = number_format(Payment::where("order_code", $orderCode)->sum("amount"), 2);
        $distribution = number_format($payment->amount, 2);
        return response()->json(compact(
            "paymentType",
            "orderCode",
            "numberOfAccounts",
            "refNumber",
            "receiptNumbers",
            "orders",
            "distribution",
            "total"
        ));
    }

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
        if (
            !$request->bearerToken() ||
            !Hash::check('P@$$worD123', $request->bearerToken())
        )
            return response(["message" => "Access Forbidden"], 403);

        if ($request->type === "full") {
            return $this->handleFullPayment($request);
        }
        if ($request->type === "split_equally") {
            return $this->handleSplitEqually($request);
        }

        return response(['message' => "Forbidden Request"], 403);
    }

    private function handleSplitEqually(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "order_code" => "required|string|min:3",
            "amount" => "required|numeric|min:1",
            "type" => "required",
            "split_count" => "required|numeric|min:0",
            "table_name" => "required|string",
            "reference_number" => "required|string|min:5",
            "receipt_number" => "required|string|min:2",
            "payment_id" => "required|string|min:5",
            "orders" => "required|array"
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Error', 'errors' => $validator->errors()], 400);
        }

        $validated = $validator->validated();
        $paymentExists = Payment::where("order_code", $validated['order_code'])
            ->where("type", $validated['type'])
            ->where("table_name", $validated['table_name'])
            ->where("split_count", $validated['split_count'])->exists();
        if ($paymentExists) {
            //insert, ref, payment
            $payment = Payment::create($validated);
            $payment->references()->create($validated);
        } else {
            //ref, payment, purchases
            $this->storePayment($validated);
        }

        return response(["message" => "OK"]);
    }

    private function handleFullPayment(Request $request)
    {
        $validated = Validator::make($request->all(), [
            "order_code" => "required|string|min:3",
            "amount" => "required|numeric|min:1",
            "receipt_number" => "required|string|min:2",
            "payment_id" => "required|string|min:5",
            "type" => "required",
            "split_count" => "nullable|numeric|min:0",
            "table_name" => "required|string",
            "reference_number" => "required|string|min:5",
            "orders" => "required|array"
        ]);
        if ($validated->fails()) {
            return response(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }

        $this->storePayment($validated->validated());
        return response(["message" => "OK"]);
    }

    private function storePayment($validated)
    {
        $payment = Payment::create($validated);
        $reference = $payment->references()->create($validated);
        $reference->purchases()->createMany($validated['orders']);
    }
}
