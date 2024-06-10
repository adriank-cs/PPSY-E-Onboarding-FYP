<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answerhistory', function (Blueprint $table) {
            $table->id("HistoryID");
            $table->unsignedBigInteger('AnswerID');
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('CompanyID');
            $table->unsignedBigInteger('PostID');
            $table->mediumText('content'); //text only
            $table->string('image')->nullable(); //image only
            $table->timestamps();
            //Soft Delete Column
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answerhistory');
    }
};
