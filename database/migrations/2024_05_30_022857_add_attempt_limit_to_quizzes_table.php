<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttemptLimitToQuizzesTable extends Migration
{
    /**
     * Run the migrations.
      */
    public function up()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->integer('attempt_limit')->default(1); // Adding the new column
        });
    }
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('attempt_limit');
        });
    }
};