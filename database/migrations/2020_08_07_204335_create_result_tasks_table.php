<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_task_id');
            $table->integer('point');
            $table->timestamps();

            $table->foreign('student_task_id')->references('id')->on('student_tasks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('result_tasks');
    }
}
