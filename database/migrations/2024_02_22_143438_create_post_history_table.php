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
            $table->string('title');
            $table->mediumText('content'); //Content may include videos, images etc. (Maximum 16MB) (Should self impose limits)
            $table->boolean('is_answered')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->boolean('is_archived')->default(false);
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
        Schema::dropIfExists('posthistory');
    }
};
