<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up()
     {
         Schema::create('employee_correct_answers', function (Blueprint $table) {
             $table->id();
             $table->unsignedBigInteger('user_id');
             $table->unsignedBigInteger('quiz_question_id');
             $table->string('answer_status')->nullable();
             $table->timestamps();
     
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
             $table->foreign('quiz_question_id')->references('id')->on('quiz_questions')->onDelete('cascade');
         });
     }
     
     public function down()
     {
         Schema::dropIfExists('employee_correct_answers');
     }     

};
