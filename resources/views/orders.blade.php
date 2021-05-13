@extends("layouts.app")

@section('main')
    <h3 class="text-center mt-5">ORDER PAYMENT SUMMARY</h3>
    <div id="orderDetails" class="py-5 px-2" style="min-height: 300px;">
        <div class="d-flex flex-wrap justify-content-start align-items-start p-2" style="min-height: 200px;">
            <v-lazy transition="fade-transition">
                <v-card class="m-2" width="250px">
                    <v-list-item two-line>
                        <v-list-item-content>
                            <v-list-item-title class="headline mb-1">
                                PAYMENT TYPE
                            </v-list-item-title>
                            <v-list-item-subtitle><b>{{ $paymentType }}</b></v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-card>
            </v-lazy>
            <v-card class="m-2" width="400px">
                <v-list-item two-line>
                    <v-list-item-content>
                        <v-list-item-title class="headline mb-1">
                            ORDER CODE
                        </v-list-item-title>
                        <v-list-item-subtitle><b>{{ $orderCode }}</b></v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </v-card>
            <v-card class="m-2" width="250px">
                <v-list-item two-line>
                    <v-list-item-content>
                        <v-list-item-title class="headline mb-1">
                            NUMBER OF ACCOUNTS
                        </v-list-item-title>
                        <v-list-item-subtitle><b>{{ $numberOfAccounts }}</b></v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </v-card>
            <v-card class=" m-2" width="400px">
                <v-list-item two-line>
                    <v-list-item-content>
                        <v-list-item-title class="headline mb-1">
                            REFERENCE NUMBER
                        </v-list-item-title>
                        <v-list-item-subtitle><b>{{ $refNumber }}</b></v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </v-card>
            <v-card class="m-2" width="250px">
                <v-list-item two-line>
                    <v-list-item-content>
                        <v-list-item-title class="headline mb-1">
                            TOTAL AMOUNT
                        </v-list-item-title>
                        <v-list-item-subtitle><b>{{ $total }}.00</b> PESO</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </v-card>
            <v-card class="m-2" width="400px">
                <v-list-item two-line>
                    <v-list-item-content>
                        <v-list-item-title class="headline mb-1">
                            DISTRIBUTION AMOUNT
                        </v-list-item-title>
                        <v-list-item-subtitle><b>{{ $distribution }}.00</b> PESO</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </v-card>
            <div style="width: 100%">
                <h5 class="m-2">Receipt Numbers</h5>
                @foreach ($receiptNumbers as $receipt)
                    <v-card class="m-2" width="500px">
                        <v-list-item two-line>
                            <v-list-item-content>
                                <v-list-item-title class="headline mb-1">
                                    RECEIPT NUMBER
                                </v-list-item-title>
                                <v-list-item-subtitle><b>{{ $receipt }}</b></v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                    </v-card>
                @endforeach
            </div>
        </div>
    </div>
    <div id="orders" class="py-5"
         style="min-height: 40vh; background: #ab97f3; border-top-left-radius: 25px; border-top-right-radius: 25px;">
        @foreach ($orders as $order)
            <v-lazy transition="fade-transition">
                <v-card class="mx-auto mb-4" max-width="80%" outlined>
                    <v-list-item three-line>
                        <v-list-item-content>
                            <div class="overline mb-4">
                                <v-chip class="ma-2" color="green" text-color="white">
                                    {{ $order->count == '1' ? $order->count . ' PIECE' : $order->count . ' PIECES' }}
                                </v-chip>
                                <v-chip class="ma-2" text-color="white">
                                    {{ $order->amount  }}.00 PESO
                                </v-chip>
                            </div>
                            <v-list-item-title class="headline mb-1">
                                {{ $order->menu->title }}

                            </v-list-item-title>
                            <v-list-item-subtitle>{{ $order->menu->ingredients }}
                            </v-list-item-subtitle>
                        </v-list-item-content>

                        <v-avatar class="bg-danger" size="95" rounded><img style="background-position: center; background-size: cover; background-repeat: no-repeat" src="{{ $order->menu->image_path }}" alt=""></v-avatar>
                    </v-list-item>
                </v-card>
            </v-lazy>
        @endforeach
    </div>
@endsection
