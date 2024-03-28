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
            $table->string('employee_id');
            $table->string('name');
            $table->string('gender');
            $table->date('dob');
            $table->integer('age');
            $table->string('position');
            $table->string('dept');
            $table->text('bio');
            $table->string('phone_no');
            $table->string('address');
            $table->string('profile_picture')->nullable();
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
        Schema::dropIfExists('profiles');
    }
};
