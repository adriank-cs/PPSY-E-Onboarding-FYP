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
        Schema::create('companyusers', function (Blueprint $table) {
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('CompanyID');
            $table->primary(['UserID', 'CompanyID']); // Combined primary key

            // Foreign key relationship with the users table
            $table->foreign('UserID')->references('id')->on('users');

            // Foreign key relationship with the companies table
            $table->foreign('CompanyID')->references('CompanyID')->on('companies');

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
        Schema::dropIfExists('companyuser');
    }
};
