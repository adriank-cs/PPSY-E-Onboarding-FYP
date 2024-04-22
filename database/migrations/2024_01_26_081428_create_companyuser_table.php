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
            //$table->ulid('CUID');
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('CompanyID');
            //Unique ULID Column
            //$table->primary('CUID');
            $table->primary(['UserID', 'CompanyID']);
            // Is Admin column
            $table->boolean('isAdmin')->default(false);
            $table->timestamps();
            //Soft Delete Column
            $table->softDeletes(); 
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
