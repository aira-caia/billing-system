<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('image_path');
            $table->timestamps();
        });

        /*\App\Models\Category::insert([
            ["title" => "Lunch", 'created_at' =>  date('Y-m-d H:i:s'), 'updated_at' =>  date('Y-m-d H:i:s')],
            ["title" => "Dinner", 'created_at' =>  date('Y-m-d H:i:s'), 'updated_at' =>  date('Y-m-d H:i:s')],
            ["title" => "Desserts", 'created_at' =>  date('Y-m-d H:i:s'), 'updated_at' =>  date('Y-m-d H:i:s')],
            ["title" => "Breakfast", 'created_at' =>  date('Y-m-d H:i:s'), 'updated_at' =>  date('Y-m-d H:i:s')],
            ["title" => "Drinks", 'created_at' =>  date('Y-m-d H:i:s'), 'updated_at' =>  date('Y-m-d H:i:s')],
            ["title" => "Alcohol", 'created_at' =>  date('Y-m-d H:i:s'), 'updated_at' =>  date('Y-m-d H:i:s')],
            ["title" => "Beer", 'created_at' =>  date('Y-m-d H:i:s'), 'updated_at' =>  date('Y-m-d H:i:s')],
        ]);*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
