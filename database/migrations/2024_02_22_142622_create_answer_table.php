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
            $table->id('AnswerID'); // Auto-incremented primary key
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('CompanyID');
            $table->unsignedBigInteger('PostID');
            $table->mediumText('content'); // text only
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('is_anonymous')->default(false)->nullable();

            // Remove the explicit definition of the composite primary key
            // $table->primary(['AnswerID', 'UserID', 'CompanyID', 'PostID']);
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
}
