<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_bank_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('exam_schedule_id');
            $table->bigInteger('answer');
            $table->longText('esay')->nullable();
            $table->boolean('doubt')->default(false);
            $table->boolean('iscorrect')->default(false);
            $table->timestamps();

            $table->foreign('question_bank_id')->references('id')->on('question_banks')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('exam_schedule_id')->references('id')->on('exam_schedules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_answers');
    }
}
