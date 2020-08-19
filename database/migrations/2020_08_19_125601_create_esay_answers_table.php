<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEsayAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('esay_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_bank_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('answer_id');
            $table->unsignedBigInteger('corrected_by');
            $table->float('point');
            $table->timestamps();

            $table->foreign('question_bank_id')->references('id')->on('question_banks')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('answer_id')->references('id')->on('student_answers')->onDelete('cascade');
            $table->foreign('corrected_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('esay_answers');
    }
}
