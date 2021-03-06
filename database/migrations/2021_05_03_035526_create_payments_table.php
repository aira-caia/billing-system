<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('order_code');
            $table->text('payment_id');
            $table->text('receipt_number');
            $table->double('amount');
            $table->string('method')->nullable();
            $table->string('type');
            $table->string('table_name');
            $table->unsignedInteger("split_count")->nullable();
            $table->boolean("is_served")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
