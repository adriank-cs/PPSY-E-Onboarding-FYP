<?php

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
//BUG: ForgetPasswordController are not available routes. Commented out for now
// Route::get('password/reset', 'Auth\ForgetPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'Auth\ForgetPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

//Route::get('/discussion/homepage', [DiscussionController::class, 'homepage'])->name('homepage'); // Display discussion homepage
Route::get('/discussion/searched', [DiscussionController::class, 'searched'])->name('searched'); // Display discussion searched question page
Route::get('/discussion/typeown', [DiscussionController::class, 'typeown'])->name('discussion.typeown'); // Display discussion searched question page
Route::get('/discussion/homepage', [PostController::class, 'homepageName'])->name('randomPost'); // Display random posts
Route::get('/discussion/typeOwn', [PostController::class, 'typeOwn'])->name('discussion.typeOwn'); // Display type own question page
Route::post('/discussion/createPost', [PostController::class, 'createPost'])->name('discussion.createPost'); // Create a new post

// Display individual post with a specific post ID
Route::get('/discussion/post/{PostID}', [PostController::class, 'postDisplay'])->name('discussion.postDisplay');

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
        Route::get('/admin/configure-module/{id}', [ModuleController::class, 'configureModule'])->name('admin.configure_module');
        Route::get('/admin/add-chapter/{moduleId}', [ModuleController::class, 'add_chapter'])->name('admin.add_chapter');
        Route::post('/admin/add-chapter/{moduleId}', [ModuleController::class, 'add_chapterPost'])->name('admin.add_chapter.post');

        //TEST ACTIONS
        Route::post('/admin/create-activity', [AdminController::class, 'createActivity'])->name('admin.create-activity');

    });

    Route::middleware(['employee'])->group(function () {
        // Routes specific to employee
        Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard'); 
        Route::get('/employee/profile-page', [EmployeeController::class, 'profile_page'])->name('employee.profile_page');
        Route::get('/employee/onboarding-home-page', [ModuleController::class, 'modules'])->name('employee.onboarding-home-page');

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



// Route::post('/modules', ModuleController::class, 'store')->name('modules.store');
// Route::resource('modules', ModuleController::class);
// Route::get('/modules/{module}/show', [ModuleController::class, 'show'])->name('modules.show');
// Route::get('/onboarding-modules/create', [ModuleController::class, 'create'])->name('modules.create');
// Route::post('/modules/{module}/submit-answers', [ModuleController::class, 'submitAnswers'])->name('modules.submit-answers');