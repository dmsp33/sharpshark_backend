<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToAlertasPlus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alertas', function (Blueprint $table) {
            $table->boolean('reviewed')->default(0)->after('url');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alertas', function (Blueprint $table) {
            $table->boolean('reviewed')->default(0)->after('url');
            $table->softDeletes();
        });
    }
}
