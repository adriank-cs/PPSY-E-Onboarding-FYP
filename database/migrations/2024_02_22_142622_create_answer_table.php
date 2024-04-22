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
            $table->mediumText('content'); //Content may include videos, images etc. (Maximum 16MB) (Should self impose limits)
            $table->timestamps();
            $table->boolean('is_anonymous')->default(false)->nullable(); //Moved to table creation
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
        Schema::dropIfExists('answer');
    }
};
