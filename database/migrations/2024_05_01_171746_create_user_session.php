<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_session', function (Blueprint $table) {
            //Session ID
            $table->ulid('id');
            //Foreign Keys
            $table->unsignedBigInteger('UserID');
            //Composite Keys
            $table->primary('id');
            //Tracks user activities
            $table->dateTime('first_activity_at')->nullable();
            $table->dateTime('last_activity_at')->nullable();
            $table->time('duration')->nullable();

            //Soft Delete Column
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_session');
    }
};
