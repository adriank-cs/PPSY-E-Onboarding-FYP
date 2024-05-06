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
        //add sidebar_color column, and button_color column to the companies table
        Schema::table('companies', function (Blueprint $table) {
            $table->string('sidebar_color')->nullable();
            $table->string('button_color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('sidebar_color');
            $table->dropColumn('button_color');
        });
    }
};
