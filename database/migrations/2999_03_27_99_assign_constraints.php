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
        //PROFILES
        Schema::table('profiles', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });

        //COMPANY USERS
        Schema::table('companyusers', function (Blueprint $table) {
            // Foreign key relationship with the users table
            $table->foreign('UserID')->references('id')->on('users');

            // Foreign key relationship with the companies table
            $table->foreign('CompanyID')->references('CompanyID')->on('companies');
        });

        //SUPERADMINS
        Schema::table('superadmins', function (Blueprint $table) {
            // Foreign key relationship with the users table
            $table->foreign('UserID')->references('id')->on('users');
        });

        //POSTS
        Schema::table('post', function (Blueprint $table) {
            // Foreign key relationship with the users table
            $table->foreign('UserID')->references('UserID')->on('companyusers');
            $table->foreign('CompanyID')->references('CompanyID')->on('companyusers');
        });

        //ANSWERS
        Schema::table('answer', function (Blueprint $table) {
            // Foreign key relationship with the users table
            $table->foreign('UserID')->references('UserID')->on('companyusers');
            $table->foreign('CompanyID')->references('CompanyID')->on('companyusers');
            $table->foreign('PostID')->references('PostID')->on('post');
        });

        //POST HISTORY
        Schema::table('posthistory', function (Blueprint $table) {
            // Foreign key relationship with the users table
            $table->foreign('PostID')->references('PostID')->on('post');
            $table->foreign('UserID')->references('UserID')->on('companyusers');
            $table->foreign('CompanyID')->references('CompanyID')->on('companyusers');
        });

        //ANSWER HISTORY
        Schema::table('answerhistory', function (Blueprint $table) {
            // Foreign key relationship with the users table
            $table->foreign('UserID')->references('UserID')->on('companyusers');
            $table->foreign('CompanyID')->references('CompanyID')->on('companyusers');
            $table->foreign('PostID')->references('PostID')->on('post');
        });

        //MODULES
        Schema::table('modules', function (Blueprint $table) {
            // Foreign key relationship with the users table
            $table->foreign('CompanyID')->references('CompanyID')->on('companyusers');
        });
        
        //MODULE QUESTIONS
        Schema::table('module_questions', function (Blueprint $table) {
            // Foreign key relationship with the modules table
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });

        //USER RESPONSE
        Schema::table('user_responses', function (Blueprint $table) {
            // Foreign key relationship with the users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Foreign key relationship with the module_questions table
            $table->foreign('module_question_id')->references('id')->on('module_questions')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // //Turn off foreign key checks
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        //PROFILES
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        //COMPANY USERS
        Schema::table('companyusers', function (Blueprint $table) {
            $table->dropForeign(['UserID']);
            $table->dropForeign(['CompanyID']);
        });

        //SUPERADMINS
        Schema::table('superadmins', function (Blueprint $table) {
            $table->dropForeign(['UserID']);
        });

        //POSTS
        Schema::table('post', function (Blueprint $table) {
            $table->dropForeign(['UserID']);
            $table->dropForeign(['CompanyID']);
        });

        //ANSWERS
        Schema::table('answer', function (Blueprint $table) {
            $table->dropForeign(['UserID']);
            $table->dropForeign(['CompanyID']);
            $table->dropForeign(['PostID']);
        });

        //POST HISTORY
        Schema::table('posthistory', function (Blueprint $table) {
            $table->dropForeign(['PostID']);
            $table->dropForeign(['UserID']);
            $table->dropForeign(['CompanyID']);
        });

        //ANSWER HISTORY
        Schema::table('answerhistory', function (Blueprint $table) {
            $table->dropForeign(['UserID']);
            $table->dropForeign(['CompanyID']);
            $table->dropForeign(['PostID']);
        });

        // //Turn back on foreign key checks
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
