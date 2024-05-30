<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ForgetPassController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ModuleController;
use App\Http\Requests\StoreModuleRequest;
use App\Http\Controllers\ColorPreferenceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

//aifei web.php//
Route::get('/forgetpassword', [ForgetPassController::class, 'forgotpassword_page'])->name('forgetpass');
//For continue login with google
Route::get('auth/google', [GoogleAuthController::class, 'redirect']) -> name('google-auth'); 
Route::get('auth/google/call-back', [GoogleAuthController::class, 'callback_google']) -> name('callback_google'); 
//--Forget Password--//
Route::get('/forgot-password-page', [ForgetPassController::class, 'forgotpassword_page']) -> name('forgotpassword_page'); //display the forgot password page
Route::post('/email-notify-page', [ForgetPassController::class, 'email_notify_page']) -> name('email_notify_page'); //display the forgot password page
//for direct to reset password page 
Route::get('/reset-password/{token}', [ForgetPassController::class, 'reset_password_page']) -> name('reset_password_page'); 
Route::post('/reset-password', [ForgetPassController::class,'reset_password'])->name('reset_password');

//For reset passsword
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');


Route::get('/color-preferences', [ColorPreferenceController::class, 'editColors'])->name('color.preferences');
Route::post('/color-preferences', [ColorPreferenceController::class, 'updateColors'])->name('color.save');


Route::middleware(['web', 'auth'])->group(function () {
    // Common authenticated user routes (both admin and employee)

    Route::middleware(['admin'])->group(function () {
        // Routes specific to admin
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard'); 
        Route::get('/admin/profile-page', [AdminController::class, 'profile_page'])->name('admin.profile_page');
        Route::get('/admin/manage-account', [AdminController::class, 'manage_account'])->name('manage_account');
        Route::get('/admin/add-account', [AdminController::class, 'add_account'])->name('add_account');
        Route::post('/admin/add-account', [AdminController::class, 'add_accountPost'])->name('add_account.post');
        Route::get('/admin/edit-account/{id}', [AdminController::class, 'editAccount'])->name('admin.edit_account');
        Route::post('/admin/edit-account/{id}', [AdminController::class, 'editAccountPost'])->name('admin.edit_account.post');
        Route::get('/admin/delete_account/{id}', [AdminController::class, 'deleteAccount'])->name('admin.delete_account');
        Route::post('/admin/update-profile', [AdminController::class, 'updateProfile'])->name('admin.update-profile');

        Route::get('/admin/manage-modules', [ModuleController::class, 'manage_modules'])->name('admin.manage_modules');
        Route::get('/admin/add-modules', [ModuleController::class, 'add_module'])->name('admin.add_module');
        Route::post('/admin/add-modules', [ModuleController::class, 'add_modulePost'])->name('admin.add_module.post');
        Route::get('/admin/edit-module/{id}', [ModuleController::class, 'editModule'])->name('admin.edit_module');
        Route::post('/admin/edit-module/{id}', [ModuleController::class, 'editModulePost'])->name('admin.edit_module.post');
        Route::get('/admin/delete_module/{id}', [ModuleController::class, 'deleteModule'])->name('admin.delete_module');

        Route::get('/admin/manage-chapter/{id}', [ModuleController::class, 'manageChapter'])->name('admin.manage_chapter');
        Route::get('/admin/add-chapter/{moduleId}', [ModuleController::class, 'add_chapter'])->name('admin.add_chapter');
        Route::post('/admin/add-chapter/{moduleId}', [ModuleController::class, 'add_chapterPost'])->name('admin.add_chapter.post');
        Route::get('/admin/edit-chapter/{id}', [ModuleController::class, 'editChapter'])->name('admin.edit_chapter');
        Route::post('/admin/edit-chapter/{id}', [ModuleController::class, 'editChapterPost'])->name('admin.edit_chapter.post');
        Route::get('/admin/delete_chapter/{id}', [ModuleController::class, 'deleteChapter'])->name('admin.delete_chapter');

        Route::get('/admin/manage-page/{id}', [ModuleController::class, 'managePage'])->name('admin.manage_page');
        Route::get('/admin/add-page/{chapterId}', [ModuleController::class, 'add_page'])->name('admin.add_page');
        Route::post('/admin/add-page/{chapterId}', [ModuleController::class, 'add_pagePost'])->name('admin.add_page.post');
        Route::get('/admin/edit-page/{id}', [ModuleController::class, 'editPage'])->name('admin.edit_page');
        Route::post('/admin/edit-page/{id}', [ModuleController::class, 'editPagePost'])->name('admin.edit_page.post');
        Route::get('/admin/delete_page/{id}', [ModuleController::class, 'deletePage'])->name('admin.delete_page');

        //DISCUSSION
        // Route::get('/admin/discussion/searched', [DiscussionController::class, 'searched'])->name('searched'); // Display discussion searched question page
        // Route::get('/admin/discussion/create-post', [DiscussionController::class, 'typeOwn'])->name('admin.create-post'); // Display discussion searched question page

        Route::get('/admin/discussion', [PostController::class, 'homepageName'])->name('randomPost'); // Display random posts   
        Route::get('/admin/discussion/create-post', [PostController::class, 'typeOwn'])->name('admin.create-post'); // Display type own question page
        Route::post('admin/discussion/create-post', [PostController::class, 'createPost'])->name('admin.createPost'); // Create a new post
        Route::post('/admin/discussion/submit-answer/{PostID}', [PostController::class, 'submitAnswer'])->name('admin.submitAnswer');
        Route::get('admin/check-post', [PostController::class, 'checkPostedQuestions'])->name('admin.check-post');
        Route::get('/admin/discussion/post/{PostID}', [PostController::class, 'postDisplay'])->name('admin.postDisplay');
        Route::get('/admin/discussion/delete-post/{PostID}', [PostController::class, 'deletePost'])->name('admin.deletePost');
        Route::get('/admin/discussion/view-history/{PostID}', [PostController::class, 'viewHistory'])->name('admin.viewHistory');
        Route::get('/admin/discussion/edit-post/{PostID}', [PostController::class, 'editPost'])->name('admin.editPost');
        Route::post('/admin/discussion/update-post/{PostID}', [PostController::class, 'updatePost'])->name('admin.updatePost');

        // //Route::get('/admin/discussion/', [DiscussionController::class, 'homepage'])->name('admin.discussion'); // Display discussion homepage
        // Route::get('/admin/discussion/', [PostController::class, 'homepageName'])->name('randomPost'); // Display random posts
        // //Route::get('/admin/discussion/typeOwn', [PostController::class, 'typeOwn'])->name('discussion.typeOwn'); // Display type own question page
        // Route::post('/admin/discussion/createPost', [PostController::class, 'createPost'])->name('admin.postDisplay'); // Create a new post

        // Route::get('/discussion/autocomplete', [PostController::class, 'autocomplete'])->name('discussion.autocomplete'); // Autocomplete route

        Route::post('/admin/upload', [AdminController::class, 'uploadImage'])->name('admin.upload_image');

        //TEST ACTIONS
        
        //TODO: REMOVE TEST ACTIONS
        Route::post('/admin/create-activity', [AdminController::class, 'createActivity'])->name('admin.create-activity');

    });

    Route::middleware(['employee'])->group(function () {
        // Routes specific to employee
        Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard'); 
        Route::get('/employee/profile-page', [EmployeeController::class, 'profile_page'])->name('employee.profile_page');
        Route::get('/employee/onboarding-home-page', [ModuleController::class, 'modules'])->name('employee.onboarding-home-page');
        Route::get('/employee/layout', [EmployeeController::class, 'layout'])->name('employee.layout'); 

    });

    Route::middleware(['superadmin'])->group(function () {
        // Routes specific to superadmin
        Route::get('/superadmin/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard'); 
        Route::get('/superadmin/profile', [SuperAdminController::class, 'profile_page'])->name('superadmin.profile_page');
        Route::get('/superadmin/manage-account', [SuperAdminController::class,'manageAccount'])->name('superadmin.manage_account');
        Route::get('/superadmin/add-account', [SuperAdminController::class, 'add_account'])->name('superadmin.add_account');
        Route::post('/superadmin/add-account', [SuperAdminController::class, 'add_accountPost'])->name('superadmin.add_account.post');
        Route::get('/superadmin/edit-account/{id}', [SuperAdminController::class, 'editAccount'])->name('superadmin.edit_account');
        Route::post('/superadmin/edit-account/{id}', [SuperAdminController::class, 'editAccountPost'])->name('superadmin.edit_account.post');
        Route::get('/superadmin/delete_account/{id}', [SuperAdminController::class, 'deleteAccount'])->name('superadmin.delete_account');
        Route::get('/superadmin/add-company', [SuperAdminController::class, 'add_company'])->name('superadmin.add_company');
        Route::post('/superadmin/add-company', [SuperAdminController::class, 'add_companyPost'])->name('superadmin.add_company.post');
        Route::get('/superadmin/manage-company', [SuperAdminController::class,'manageCompany'])->name('superadmin.manage_company');
        Route::get('/superadmin/edit-company/{id}', [SuperAdminController::class, 'editCompany'])->name('superadmin.edit_company');
        Route::post('/superadmin/edit-company/{id}', [SuperAdminController::class, 'editCompanyPost'])->name('superadmin.edit_company.post');
        Route::get('/superadmin/delete-company/{id}', [SuperAdminController::class, 'deleteCompany'])->name('superadmin.delete_company');
    });
});

//Route::get('/employee/onboarding-home-page', [EmployeeController::class, 'onboarding_home_page'])->name('onboarding_home_page');;//display the homepage 
//Route::get('/onboarding-modules/create', [ModuleController::class, 'create']);


Route::resource('quizzes', QuizController::class);
Route::get('/employee/onboarding-quiz', [QuizController::class, 'index'])->name('employee.onboarding-quiz');

Route::get('/quizzes/{quiz}/show', [QuizController::class, 'show'])->name('quizzes.show');
Route::get('/onboarding-quiz/create', [QuizController::class, 'create']);


Route::post('/quizzes/{quiz}/submit-answers', [QuizController::class, 'submitAnswers'])->name('quizzes.submit-answers');

Route::get('/quizzes/{quiz}/details', [QuizController::class, 'getDetails'])->name('quizzes.get-details');

