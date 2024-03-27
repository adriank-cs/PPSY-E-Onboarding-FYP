<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('answer', function (Blueprint $table) {
            $table->boolean('is_anonymous')->default(false)->nullable();
        });

        

    }
};
