<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_banks', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100);
            $table->bigInteger('standart_id')->default(0);
            $table->integer('mc_count')->comment('multiple choice count');
            $table->integer('mc_option_count')->default(4)->comment('multple choise option count');
            $table->integer('esay_count')->default(0);
            $table->text('percentage');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('author');

            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('author')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('question_banks');
    }
}
