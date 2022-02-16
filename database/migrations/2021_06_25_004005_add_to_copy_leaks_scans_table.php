<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToCopyLeaksScansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('copy_leaks_scans', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->nullable()->default(1)->after('id');
            $table->boolean('audited')->nullable()->default(true)->after('body');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('copy_leaks_scans', function (Blueprint $table) {
            //
        });
    }
}
