<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addOrdertoItemProgressTable extends Migration
{
    public function up()
{
    Schema::table('item_progress', function (Blueprint $table) {
        $table->integer('order')->default(0);
    });
}

public function down()
{
    Schema::table('item_progress', function (Blueprint $table) {
        $table->dropColumn('order');
    });
}
};
