<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use App\Http\Resources\PurchaseResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{


    public function orders()
    {
        // This method will return the details of our transactions group by order code.
        $payments = PaymentResource::collection(Payment::orderBy("is_served")->groupBy("order_code")->get());
        return $payments;
    }

    public function payments()
    {
        // This method will also return the details of our all transactions.
        $payments = PaymentResource::collection(Payment::all());
        return $payments;
    }


    public function update(Payment $payment)
    {
        //This method is called , when we toggle the served/not serve button on queueing module of mobile app
        Payment::where("order_code", $payment->order_code)->update(["is_served" => !$payment->is_served]);
        return response(["message" => "OK"]);
    }

    public function show(Request $request, $orderCode)
    {
        //This method will show a specific transaction details base on order code provided.

        //The request is only possible if it carries a bearer token, equal to this data below.
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

    public function store(Request $request)
    {
        //This method is responsible for storing payment transactions to our database


        if (
            !$request->bearerToken() ||
            !Hash::check('P@$$worD123', $request->bearerToken())
        )
            return response(["message" => "Access Forbidden"], 403);

        //Depending on payment type we will handle them differently
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
        //If the payment type is split equally, this method is called
        //We're gonna store these bunch of data to our database
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
        //If the payment type is full payment, this method is called
        //We're gonna store these bunch of data also to our database
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
        //this method is called when we successfully satisfied the validation for each payment.
        $payment = Payment::create($validated);
        $reference = $payment->references()->create($validated);
        $reference->purchases()->createMany($validated['orders']);
    }
}
