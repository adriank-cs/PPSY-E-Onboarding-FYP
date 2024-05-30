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
        Schema::create('companies', function (Blueprint $table) {
            $table->id('CompanyID'); // Primary Key
            $table->string('Name');
            $table->string('Industry');
            $table->text('Address');
            $table->string('Website');

            //Multi-Tenancy Feature
            $table->string('sidebar_color')->nullable();
            $table->string('button_color')->nullable();
            $table->string('company_logo')->nullable();

            //Subscription Feature
            $table->timestamp('subscription_starts_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();

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
        Schema::dropIfExists('companies');
    }
};
