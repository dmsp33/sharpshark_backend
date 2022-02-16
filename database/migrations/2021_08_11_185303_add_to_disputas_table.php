<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToDisputasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disputas', function (Blueprint $table) {
            $table->enum('contact_type', ['email', 'form', 'nothing'])->after('your_web_archive');
            $table->enum('type', ['alert', 'website', 'provider', 'search' , 'ended'])->after('money_type');
            $table->foreignId('alerta_id')->constrained()->after('id');
            $table->json('email')->nullable()->after('id');
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
            //
        });
    }
}
