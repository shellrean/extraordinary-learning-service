<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('papers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classroom_subject_id');
            $table->unsignedBigInteger('teacher_id');
            $table->enum('type',['lesson_plan','syllabus']);
            $table->string('name')->nullable();
            $table->text('body')->nullable();
            $table->string('file_location')->nullable();
            $table->text('settings')->nullable();
            $table->timestamps();

            $table->foreign('classroom_subject_id')->references('id')->on('classroom_subjects')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('papers');
    }
}
