<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreparationTimeToMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->decimal('previous_price')->nullable();
            $table->unsignedInteger('preparation_time')->nullable();
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->foreignId('menu_id')->constrained();
            $table->unsignedBigInteger('one_star')->default(0);
            $table->unsignedBigInteger('two_star')->default(0);
            $table->unsignedBigInteger('three_star')->default(0);
            $table->unsignedBigInteger('four_star')->default(0);
            $table->unsignedBigInteger('five_star')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            //
        });
    }
}
