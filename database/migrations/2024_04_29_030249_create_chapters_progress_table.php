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
        Schema::create('chapters_progress', function (Blueprint $table) {
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('CompanyID');
            $table->unsignedBigInteger('ModuleID');
            $table->unsignedBigInteger('ChapterID');
            $table->primary(['UserID', 'CompanyID', 'ModuleID', 'ChapterID']);
            $table->boolean('IsCompleted')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters_progress');
    }
};
