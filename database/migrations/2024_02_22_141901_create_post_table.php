<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->id('PostID'); // This will create an auto-incrementing primary key named 'id'
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('CompanyID');
            $table->string('title');
            $table->mediumText('content'); //text only
            $table->boolean('is_answered')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->boolean('is_anonymous')->default(false)->nullable(); //Moved to table creation
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
        Schema::dropIfExists('post');
    }
};
