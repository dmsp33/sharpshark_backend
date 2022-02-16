<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableToDisputasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disputas', function (Blueprint $table) {
            $table->string('screenshot')->nullable()->change();
            $table->decimal('amount', $precision = 8, $scale = 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disputas', function (Blueprint $table) {
            $table->string('screenshot')->change();
            $table->decimal('amount', $precision = 8, $scale = 2)->change();
        });
    }
}
