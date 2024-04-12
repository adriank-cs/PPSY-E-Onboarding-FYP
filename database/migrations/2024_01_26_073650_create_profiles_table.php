<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id('profile_id'); //Profile ID as primary key
            $table->unsignedBigInteger('user_id'); //ID as foreign key linked to the users table
            $table->string('employee_id')->nullable(); //TODO: Best to keep Employee ID as non-nullable
            $table->string('name');
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->integer('age')->nullable();
            $table->string('position')->nullable();
            $table->string('dept')->nullable();
            $table->text('bio')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('address')->nullable();
            $table->string('profile_picture')->nullable();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
};
