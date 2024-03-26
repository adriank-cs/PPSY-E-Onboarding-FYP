<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posthistory', function (Blueprint $table) {
            $table->id('HistoryID');
            $table->unsignedBigInteger('PostID');
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('CompanyID');

            $table->foreign('PostID')->references('PostID')->on('post');
            $table->foreign('UserID')->references('UserID')->on('companyusers');
            $table->foreign('CompanyID')->references('CompanyID')->on('companyusers');
            
            $table->string('title');
            $table->text('content');
            $table->boolean('is_answered')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->boolean('is_archived')->default(false);
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
        Schema::dropIfExists('posthistory');
    }
};
