<?php
/* 2024_03_07_132651_create_module_questions_table */
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
        Schema::create('module_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id');
            $table->text('question');
            $table->string('type', 20); // Add a column to store question type (e.g., multiple_choice, structured)
            $timestamps = [];
            if (!empty (config('broadcasting.connections.pusher'))) {
                $table->timestamps();
            }
            //Soft Delete Column
            $table->softDeletes();
            //$table->timestamps(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_questions');
    }
};
