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
            $table->primary(['UserID', 'CompanyID']); //TODO: Change composite key since company user cannot be truncated
            // Is Admin column
            $table->boolean('isAdmin')->default(false);
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
        Schema::dropIfExists('companyusers');
    }
};
