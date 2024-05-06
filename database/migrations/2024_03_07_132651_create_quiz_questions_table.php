<?php
/* 2024_03_07_132651_create_quiz_questions_table */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Timestamps;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->text('question');
            $table->string('type', 20); // Add a column to store question type (e.g., multiple_choice, structured)
            $table->json('answer_options')->nullable();
            $timestamps = [];
            if (!empty (config('broadcasting.connections.pusher'))) {
                $table->timestamps();
            }
            //$table->timestamps(1);
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_questions');
    }
};
