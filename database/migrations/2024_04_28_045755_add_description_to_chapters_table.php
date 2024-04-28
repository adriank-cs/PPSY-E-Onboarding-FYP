<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('chapters', function (Blueprint $table) {
            // Add description column after title
            $table->text('description')->nullable()->after('title');
        });
    }

    public function down()
    {
        Schema::table('chapters', function (Blueprint $table) {
            // Drop the description column if the migration is rolled back
            $table->dropColumn('description');
        });
    }
};
