d<?php

    use App\Models\Menu;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class AddIsAvailableToMenus extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('menus', function (Blueprint $table) {
                $table->boolean('is_available')->default(1)->nullable();
            });
            Menu::where('id', '>=', 1)->update(['is_available' => 1]);
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('menu', function (Blueprint $table) {
                //
            });
        }
    }
