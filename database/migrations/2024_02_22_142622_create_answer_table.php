<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer', function (Blueprint $table) {
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('CompanyID');
            $table->unsignedBigInteger('PostID');
            $table->primary(['UserID', 'CompanyID', 'PostID']);

            $table->foreign('UserID')->references('UserID')->on('companyusers');
            $table->foreign('CompanyID')->references('CompanyID')->on('companyusers');
            $table->foreign('PostID')->references('PostID')->on('post');

            $table->text('content');
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
        Schema::dropIfExists('answer');
    }
};
