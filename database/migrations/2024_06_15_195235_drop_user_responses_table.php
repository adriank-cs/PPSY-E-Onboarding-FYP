<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user_responses');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // You can define how to revert the migration if needed
        Schema::create('user_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('quiz_question_id');
            $table->text('answer')->nullable();
            $table->string('user_answer_option_id')->nullable(); // Add this line to store answer option id as JSON
            $table->timestamps();

            //Soft Delete Column
            $table->softDeletes();
        });
    }
};
