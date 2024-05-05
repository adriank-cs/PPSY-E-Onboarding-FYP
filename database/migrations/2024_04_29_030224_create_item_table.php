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
        Schema::create('item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("chapter_id"); // Reference chapter_id from chapters table
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->dateTime('due_date')->nullable(); //Expected completion date and time
            $table->timestamps();
            //Soft Delete Column
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item');
    }
};
