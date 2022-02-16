<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopyLeaksResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copy_leaks_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('copy_leaks_scan_id')->constrained();
            $table->string('url')->nullable();
            $table->text('title')->nullable();
            $table->text('introduction')->nullable();
            $table->string('matchedWords')->nullable();
            $table->boolean('plagarism');
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
        Schema::dropIfExists('copy_leaks_responses');
    }
}