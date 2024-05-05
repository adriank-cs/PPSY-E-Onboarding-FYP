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
        Schema::create('assigned_module', function (Blueprint $table) {
            $table->unsignedBigInteger('UserID'); // Reference user id and company id from company user table
            $table->unsignedBigInteger('CompanyID');
            $table->unsignedBigInteger('ModuleID'); // Reference module id from module table
            $table->primary(['UserID', 'CompanyID', 'ModuleID']);
            $table->dateTime('DateAssigned');
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
        Schema::dropIfExists('assigned_module');
    }
};
