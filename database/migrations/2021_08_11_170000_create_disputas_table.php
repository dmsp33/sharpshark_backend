<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisputasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disputas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('SET NULL');
            $table->string('claim_for');
            $table->string('jurisdiction');
            $table->date('discovered');
            $table->string('screenshot');
            $table->boolean('remove_content');
            $table->boolean('acknowledge');
            $table->boolean('pay_for_use');
            $table->decimal('amount', $precision = 8, $scale = 2);
            $table->string('money_type');
            $table->boolean('conditions_default')->nullable();
            $table->string('certificate_authorship');
            $table->string('screenshot_draft')->nullable();
            $table->json('in_question');
            $table->json('in_question_web_archive');
            $table->json('your_publication')->nullable();
            $table->json('your_web_archive')->nullable();

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
        Schema::dropIfExists('disputas');
    }
}
