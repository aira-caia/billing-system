<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use App\Http\Resources\PurchaseResource;
use App\Http\Resources\WebPaymentResource;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Menu;

class PaymentController extends Controller
{

    // This method will return the details of our transactions group by order code.
    public function orders(Request $request)
    {
        if (
            !$request->bearerToken() ||
            !Hash::check('P@$$worD123', $request->bearerToken())
        )
            return response(["message" => "Access Forbidden"], 403);

        if ($request->is_served == "1") {
            $payments = PaymentResource::collection(Payment::orderByDesc("created_at")->whereDate('created_at', '>', Carbon::today()->subDays(1))->where("is_served", 1)->groupBy("order_code", 'is_served', 'created_at')->get());
        } else if ($request->is_served == "0") {
            $payments = PaymentResource::collection(Payment::orderBy("is_served")->whereDate('created_at', '>', Carbon::today()->subDays(1))->where("is_served", 0)->groupBy("order_code", 'is_served', 'created_at')->get());
        } else {
            $payments = PaymentResource::collection(Payment::orderByDesc("created_at")->whereDate('created_at', '>', Carbon::today()->subDays(1))->groupBy("order_code", 'is_served', 'created_at')->get());
        }
        // return ['data' => []];
        return $payments;
    }

    public function payments()
    {
        // This method will also return the details of our all transactions.
        $payments = PaymentResource::collection(Payment::all());
        return $payments;
    }

    public function webPayments()
    {
        // This method will also return the details of our all transactions.
        $payments = WebPaymentResource::collection(Payment::all());
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


        if ($payment == null) {
            return response()->json(['error' => 'Invalid order code.'], 401);
        }


        if ($payment->type === "split_item") {
            $payments = Payment::where("order_code", $orderCode)->orderBy("id")->get();
            $orders = collect();
            $payments->each(function ($p) use (&$orders) {
                $stores = PurchaseResource::collection($p->references->first()->purchases);
                $load = collect();
                $stores->each(function ($store) use (&$orders, &$load) {
                    $store['title'] = $store->menu->title;
                    $store['image'] = $store->menu->image_path;
                    $store['ingredients'] = $store->menu->ingredients ?? "Not Specified";
                    if ($orders->contains("menu_id", "=", $store->menu_id)) {
                        $totalCount = intval($orders->firstWhere("menu_id", "=", $store->menu_id)->count) + $store->count;
                        $totalAmount = floatval($orders->firstWhere("menu_id", "=", $store->menu_id)->amount) + $store->amount;
                        $orders->firstWhere("menu_id", "=", $store->menu_id)->count = $totalCount == 1 ? $totalCount . " PIECE" : $totalCount . " PIECES";
                        $orders->firstWhere("menu_id", "=", $store->menu_id)->amount = number_format($totalAmount, 2);
                    } else {
                        $store['count'] = $store->count == 1 ? $store->count . " PIECE" : $store->count . " PIECES";
                        $store['amount'] =  number_format($store->amount, 2);
                        $load = $load->concat($store);
                    }
                });
                if ($load->count() > 0) {
                    $orders = $orders->concat($load->filter(function ($value) {
                        return $value != null;
                    }));
                }
            });
        } else {
            $orders = PurchaseResource::collection($payment->references->first()->purchases);
        }


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
            "total",
        ));
    }

    public function receiptWeb(Request $request, $receiptNumber)
    {
        //This method will show a specific transaction details base on order code provided.
        //The request is only possible if it carries a bearer token, equal to this data below.
        if (
            !$request->bearerToken() ||
            !Hash::check('P@$$worD123', $request->bearerToken())
        )
            return response(["message" => "Access Forbidden"], 403);

        $payment = Payment::where("receipt_number", $receiptNumber)->orderBy("id")->first();
        if ($payment == null) {
            abort(403);
        }

        if ($payment->type === "split_item") {
            $payments = Payment::where("receipt_number", $receiptNumber)->orderBy("id")->get();
            $orders = collect();
            $payments->each(function ($p) use (&$orders) {
                $stores = PurchaseResource::collection($p->references->first()->purchases);
                $load = collect();
                $stores->each(function ($store) use (&$orders, &$load) {
                    $store['title'] = $store->menu->title;
                    $store['image'] = $store->menu->image_path;
                    $store['ingredients'] = $store->menu->ingredients ?? "Not Specified";
                    if ($orders->contains("menu_id", "=", $store->menu_id)) {
                        $totalCount = intval($orders->firstWhere("menu_id", "=", $store->menu_id)->count) + $store->count;
                        $totalAmount = floatval($orders->firstWhere("menu_id", "=", $store->menu_id)->amount) + $store->amount;
                        $orders->firstWhere("menu_id", "=", $store->menu_id)->count = $totalCount == 1 ? $totalCount . " PIECE" : $totalCount . " PIECES";
                        $orders->firstWhere("menu_id", "=", $store->menu_id)->amount = number_format($totalAmount, 2);
                    } else {
                        $store['count'] = $store->count == 1 ? $store->count . " PIECE" : $store->count . " PIECES";
                        $store['amount'] =  number_format($store->amount, 2);
                        $load = $load->concat($store);
                    }
                });
                if ($load->count() > 0) {
                    $orders = $orders->concat($load->filter(function ($value) {
                        return $value != null;
                    }));
                }
            });
        } else if ($payment->type === "split_equally") {
            $myPayment = Payment::where("order_code", $payment->order_code)->orderBy("id")->first();
            $orders = PurchaseResource::collection($myPayment->references->first()->purchases);
        } else {
            $orders = PurchaseResource::collection($payment->references->first()->purchases);
        }




        if ($payment->type === "full") $paymentType = "FULL PAYMENT";
        elseif ($payment->type === "split_equally") $paymentType = "SPLIT EQUALLY";
        else $paymentType = "SPLIT BY ITEM";

        $orderCode = $payment->order_code;
        /*
        $numberOfAccounts = Payment::where("order_code", $orderCode)->count();
        $receiptNumbers = Payment::where("order_code", $orderCode)->pluck("receipt_number"); */
        $refNumber = $payment->references->first()->reference_number;
        $total = number_format(Payment::where("receipt_number", $receiptNumber)->sum("amount"), 2);
        $realTotal = Payment::where("receipt_number", $receiptNumber)->sum("amount");
        // $distribution = number_format($payment->amount, 2);
        $tableName = $payment->table_name;
        $date = $payment->created_at->format("M d, Y");
        $time = $payment->created_at->format("h:i A");
        $count = $payment->split_count;
        return response()->json(compact(
            "paymentType",
            "orderCode",
            // "numberOfAccounts",
            "refNumber",
            // "receiptNumbers",
            "orders",
            "count",
            // "distribution",
            "total",
            "realTotal",
            "tableName",
            "date",
            "time",
        ));
    }

    public function receipt(Request $request, $receiptNumber)
    {
        //This method will show a specific transaction details base on order code provided.
        //The request is only possible if it carries a bearer token, equal to this data below.
        if (
            !$request->bearerToken() ||
            !Hash::check('P@$$worD123', $request->bearerToken())
        )
            return response(["message" => "Access Forbidden"], 403);

        $payment = Payment::where("receipt_number", $receiptNumber)->orderBy("id")->first();
        if ($payment == null) {
            abort(403);
        }

        if ($payment->type === "split_item") {
            $payments = Payment::where("receipt_number", $receiptNumber)->orderBy("id")->get();
            $orders = collect();
            $payments->each(function ($p) use (&$orders) {
                $stores = PurchaseResource::collection($p->references->first()->purchases);
                $load = collect();
                $stores->each(function ($store) use (&$orders, &$load) {
                    $store['title'] = $store->menu->title;
                    $store['image'] = $store->menu->image_path;
                    $store['ingredients'] = $store->menu->ingredients ?? "Not Specified";
                    if ($orders->contains("menu_id", "=", $store->menu_id)) {
                        $totalCount = intval($orders->firstWhere("menu_id", "=", $store->menu_id)->count) + $store->count;
                        $totalAmount = floatval($orders->firstWhere("menu_id", "=", $store->menu_id)->amount) + $store->amount;
                        $orders->firstWhere("menu_id", "=", $store->menu_id)->count = $totalCount == 1 ? $totalCount . " PIECE" : $totalCount . " PIECES";
                        $orders->firstWhere("menu_id", "=", $store->menu_id)->amount = number_format($totalAmount, 2);
                    } else {
                        $store['count'] = $store->count == 1 ? $store->count . " PIECE" : $store->count . " PIECES";
                        $store['amount'] =  number_format($store->amount, 2);
                        $load = $load->concat($store);
                    }
                });
                if ($load->count() > 0) {
                    $orders = $orders->concat($load->filter(function ($value) {
                        return $value != null;
                    }));
                }
            });
        } else {
            $orders = PurchaseResource::collection($payment->references->first()->purchases);
        }


        if ($payment->type === "full") $paymentType = "FULL PAYMENT";
        elseif ($payment->type === "split_equally") $paymentType = "SPLIT EQUALLY";
        else $paymentType = "SPLIT BY ITEM";

        $orderCode = $payment->order_code;
        /*
        $numberOfAccounts = Payment::where("order_code", $orderCode)->count();
        $receiptNumbers = Payment::where("order_code", $orderCode)->pluck("receipt_number"); */
        $refNumber = $payment->references->first()->reference_number;
        $total = number_format(Payment::where("receipt_number", $receiptNumber)->sum("amount"), 2);
        $realTotal = Payment::where("receipt_number", $receiptNumber)->sum("amount");
        // $distribution = number_format($payment->amount, 2);
        $tableName = $payment->table_name;
        $date = $payment->created_at->format("M d, Y");
        $time = $payment->created_at->format("h:i A");
        $count = $payment->split_count;
        return response()->json(compact(
            "paymentType",
            "orderCode",
            // "numberOfAccounts",
            "refNumber",
            // "receiptNumbers",
            "orders",
            "count",
            // "distribution",
            "total",
            "realTotal",
            "tableName",
            "date",
            "time",
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
        if ($request->type === "full" || $request->type === "split_item") {
            return $this->handleFullPayment($request);
        }

        if ($request->type === "split_equally" && $request->get('method') !== 'paypal') {
            return $this->handleSplitEqually($request);
        }

        if ($request->type === "split_equally" && $request->get('method') === 'paypal') {
            return $this->handleSplitEquallyPaypal($request);
        }

        return response(['message' => "Forbidden Request"], 403);
    }

    private function handleSplitEquallyPaypal(Request $request)
    {
        //If the payment type is split equally, this method is called
        //We're gonna store these bunch of data to our database
        $validator = Validator::make($request->all(), [
            "order_code" => "required|string|min:3",
            "amount" => "required|numeric|min:1",
            "type" => "required",
            "method" => "required",
            "split_count" => "required|numeric|min:0",
            "table_name" => "required|string",
            //            "reference_number" => "required|string|min:5",
            //            "receipt_number" => "required|string|min:2",
            //            "payment_id" => "required|string|min:5",
            "orders" => "required|array"
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Error', 'errors' => $validator->errors()], 400);
        }

        $validated = $validator->validated();

        $gateway = new \Braintree\Gateway([
            'environment' => 'sandbox',
            'merchantId' => 'mhjfnw88grqcb72s',
            'publicKey' => 'snk3hfq997gxjx53',
            'privateKey' => '89ba1cfaebef49533c19e1d02e5d0523'
        ]);

        $transaction = $gateway->transaction()->sale([
            'amount' => $request->amount,
            'paymentMethodNonce' => $request->nonce,
            'deviceData' => 'default',
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        $validated['payment_id'] = $transaction->transaction->id;
        $validated['receipt_number'] = $transaction->transaction->paypal['captureId'];
        $validated['reference_number'] = $transaction->transaction->paypal['paymentId'];

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

        return response(["message" => "OK", 'receipt_number' => $transaction->transaction->paypal['captureId']]);
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

    //If the payment type is full payment, this method is called
    //We're gonna store these bunch of data also to our database
    private function handleFullPayment(Request $request)
    {
        if ($request->get('method') === 'paypal') {
            $validated = Validator::make($request->all(), [
                "type" => "required",
                "method" => "required",
                "nonce" => "required|string",
                "order_code" => "required|string|min:3",
                "amount" => "required|numeric|min:1",
                "table_name" => "required|string",
                "orders" => "required|array",
                //                "receipt_number" => "required|string|min:2",
                //                "payment_id" => "required|string|min:5",
                //                "split_count" => "nullable|numeric|min:0",
                //                "reference_number" => "required|string|min:5",
            ]);
            $gateway = new \Braintree\Gateway([
                'environment' => 'sandbox',
                'merchantId' => 'mhjfnw88grqcb72s',
                'publicKey' => 'snk3hfq997gxjx53',
                'privateKey' => '89ba1cfaebef49533c19e1d02e5d0523'
            ]);
            $transaction = $gateway->transaction()->sale([
                'amount' => $request->amount,
                'paymentMethodNonce' => $request->nonce,
                'deviceData' => 'default',
                'options' => [
                    'submitForSettlement' => true
                ]
            ]);
            //            $validated['payment_id'] = $transaction['transaction']['id'];
            //            $validated['receipt_number'] = $transaction['paypal']['captureId'];
            //            $validated['reference_number'] = $transaction['paypal']['paymentId'];

        } else {
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
        }

        if ($validated->fails()) {
            return response(['message' => 'Error', 'errors' => $validated->errors()], 400);
        }
        $data = $validated->validated();
        if ($request->get('method') === 'paypal') {
            $data['payment_id'] = $transaction->transaction->id;
            $data['receipt_number'] = $transaction->transaction->paypal['captureId'];
            $data['reference_number'] = $transaction->transaction->paypal['paymentId'];
        } else {
            $data['method'] = 'paymaya';
        }
        $this->storePayment($data);
        return response(["message" => "OK"]);
    }

    private function storePayment($validated)
    {
        //this method is called when we successfully satisfied the validation for each payment.
        $payment = Payment::create($validated);
        $reference = $payment->references()->create($validated);
        $reference->purchases()->createMany($validated['orders']);
        foreach ($validated['orders'] as $order) {
            Menu::find($order['menu_id'])->decrement('quantity', $order['count']);
        }
    }
}
