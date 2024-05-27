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
        //USERS
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('last_active_session')->references('id')->on('user_session')->onDelete('cascade');
        });

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
            $table->foreign('quiz_question_id')->references('id')->on('quiz_questions')->onDelete('cascade');
        });

        //CHAPTERS
        Schema::table('chapters', function (Blueprint $table) {
            // Foreign key relationship with the modules table
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');            
        });

        //ITEM
        Schema::table('item', function (Blueprint $table) {
            // Foreign key relationship with the chapters table
            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');            
        });

        //ASSIGNED MODULE
        Schema::table('assigned_module', function (Blueprint $table) {
            // Foreign key relationship with the company users table
            $table->foreign('UserID')->references('UserID')->on('companyusers')->onDelete('cascade');
            $table->foreign('CompanyID')->references('CompanyID')->on('companyusers')->onDelete('cascade'); 
            // Foreign key relationship with the modules table
            $table->foreign('ModuleID')->references('id')->on('modules')->onDelete('cascade');      
        });

        //CHAPTER PROGRESS
        Schema::table('chapters_progress', function (Blueprint $table) {
            // Foreign key relationship with the assigned modules table
            $table->foreign('UserID')->references('UserID')->on('assigned_module')->onDelete('cascade');
            $table->foreign('CompanyID')->references('CompanyID')->on('assigned_module')->onDelete('cascade'); 
            $table->foreign('ModuleID')->references('ModuleID')->on('assigned_module')->onDelete('cascade');   
            // Foreign key relationship with the assigned chapters table   
            $table->foreign('ChapterID')->references('id')->on('chapters')->onDelete('cascade');  
        });

        //ITEM PROGRESS
        Schema::table('item_progress', function (Blueprint $table) {
            // Foreign key relationship with the assigned modules table
            $table->foreign('UserID')->references('UserID')->on('assigned_module')->onDelete('cascade');
            $table->foreign('CompanyID')->references('CompanyID')->on('assigned_module')->onDelete('cascade'); 
            $table->foreign('ModuleID')->references('ModuleID')->on('assigned_module')->onDelete('cascade');   
            // Foreign key relationship with the assigned item table   
            $table->foreign('ItemID')->references('id')->on('item')->onDelete('cascade');  
        });

        //USER SESSION
        Schema::table('user_session', function (Blueprint $table) {
            // Foreign key relationship with the company users table
            $table->foreign('UserID')->references('id')->on('users')->onDelete('cascade');
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

        //USERS
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['last_active_session']);
        });

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

        
        //MODULES
        Schema::table('modules', function (Blueprint $table) {
            $table->dropForeign(['CompanyID']);
        });
        
        //MODULE QUESTIONS
        Schema::table('module_questions', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
        });

        //USER RESPONSE
        Schema::table('user_responses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['module_question_id']);
        });

        //CHAPTERS
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropForeign(['module_id']);            
        });

        //ITEM
        Schema::table('item', function (Blueprint $table) {
            $table->dropForeign(['chapter_id']);            
        });

        //ASSIGNED MODULE
        Schema::table('assigned_module', function (Blueprint $table) {
            $table->dropForeign(['UserID']);
            $table->dropForeign(['CompanyID']); 
            $table->dropForeign(['ModuleID']);      
        });

        //CHAPTER PROGRESS
        Schema::table('chapters_progress', function (Blueprint $table) {
            $table->dropForeign(['UserID']);
            $table->dropForeign(['CompanyID']); 
            $table->dropForeign(['ModuleID']);   
            $table->dropForeign(['ChapterID']);  
        });

        //ITEM PROGRESS
        Schema::table('item_progress', function (Blueprint $table) {
            $table->dropForeign(['UserID']);
            $table->dropForeign(['CompanyID']); 
            $table->dropForeign(['ModuleID']);   
            $table->dropForeign(['ItemID']);  
        });

        //USER SESSION
        Schema::table('user_session', function (Blueprint $table) {
            $table->dropForeign(['UserID']);
        });

        // //Turn back on foreign key checks
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
