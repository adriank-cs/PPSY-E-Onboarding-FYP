<?php

use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\EmployeePostController;
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

Route::post('/admin/upload', [AdminController::class, 'uploadImage'])->name('admin.upload_image');


Route::get('/color-preferences', [ColorPreferenceController::class, 'editColors'])->name('color.preferences');
Route::post('/color-preferences', [ColorPreferenceController::class, 'updateColors'])->name('color.save');

//Chatbot Controller Routes
Route::match(['get', 'post'], '/botman', [ChatBotController::class, 'handle']);
Route::match(['get', 'post'], '/botman/chat', [ChatBotController::class, 'frame']);

Route::middleware(['web', 'auth'])->group(function () {
    // Common authenticated user routes (both admin and employee)

    Route::middleware(['admin'])->group(function () {
        // Routes specific to admin
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/progress-tracking', [AdminController::class, 'progressTracking'])->name('admin.progress-tracking');

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
        // Route::post('/admin/upload_image', [ModuleController::class, 'uploadImage'])->name('admin.upload_image');


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
        Route::post('admin/update_page_order', [ModuleController::class, 'updatePageOrder'])->name('admin.update_page_order');

        //Quiz
        Route::post('/admin/create_quiz/{chapterId}', [ModuleController::class, 'add_quizPost'])->name('admin.create_quiz.post');
        Route::post('/admin/update-quiz/{id}', [ModuleController::class, 'updateQuiz'])->name('admin.update_quiz');


        Route::get('/admin/view-page/{id}', [ModuleController::class, 'viewPage'])->name('admin.view_page');
        Route::post('admin/next-page/{itemId}', [ModuleController::class, 'nextPage'])->name('admin.next_page');   

        Route::get('/admin/assign-module/{id}', [ModuleController::class, 'assignModule'])->name('admin.assign_module');
        Route::post('/admin/assign-module/', [ModuleController::class, 'assignModulePost'])->name('admin.assign_module.post');
        Route::get('admin/unassign_module/{id}', [ModuleController::class, 'unassignModule'])->name('admin.unassign_module');
        Route::post('/admin/unassign-module/', [ModuleController::class, 'unassignModulePost'])->name('admin.unassign_module.post');
        Route::get('admin/configure-duedate/{id}', [ModuleController::class, 'configureDueDate'])->name('admin.configure_duedate');
        Route::post('/admin/configure-duedate/', [ModuleController::class, 'configureDueDatePost'])->name('admin.configure_duedate.post');
        Route::get('/admin/get-due-date/{moduleId}/{userId}', [ModuleController::class, 'getDueDate'])->name('admin.get_due_date');
        Route::get('/admin/module-complete/{moduleId}', [ModuleController::class, 'moduleComplete'])->name('admin.module_complete');

        Route::post('admin/upload-pdf', [ModuleController::class, 'uploadPdf'])->name('admin.upload_pdf');
        
        Route::get('/admin/find-colleagues', [AdminController::class, 'findColleagues'])->name('admin.find_colleagues');
        Route::get('/admin/colleague-details/{id}', [AdminController::class, 'colleagueDetails'])->name('admin.colleague_details');


        //DISCUSSION
        Route::get('/admin/discussion', [PostController::class, 'homepageName'])->name('randomPost'); // Display random posts on the homepage
        Route::get('/admin/discussion/create-post', [PostController::class, 'typeOwn'])->name('admin.create-post'); // Display the form for creating a new post
        Route::post('/admin/discussion/create-post', [PostController::class, 'createPost'])->name('admin.createPost'); // Handle the submission of a new post
        Route::post('/admin/discussion/submit-answer/{PostID}', [PostController::class, 'submitAnswer'])->name('admin.submitAnswer'); // Handle the submission of a new answer to a post
        Route::get('/admin/check-post', [PostController::class, 'checkPostedQuestions'])->name('admin.check-post'); // Display posts based on a filter (all posts or my questions)
        Route::get('/admin/discussion/post/{PostID}', [PostController::class, 'postDisplay'])->name('admin.postDisplay'); // Display a specific post along with its answers
        Route::get('/admin/discussion/delete-post/{PostID}', [PostController::class, 'deletePost'])->name('admin.deletePost'); // Handle the deletion of a post
        Route::get('/admin/discussion/view-history/{PostID}', [PostController::class, 'viewHistory'])->name('admin.viewHistory'); // View the history of a specific post
        Route::get('/admin/discussion/edit-post/{PostID}', [PostController::class, 'editPost'])->name('admin.editPost'); // Display the form for editing a specific post
        Route::post('/admin/discussion/update-post/{PostID}', [PostController::class, 'updatePost'])->name('admin.updatePost'); // Handle the update of a specific post
        Route::get('/admin/discussion/edit-answer/{AnswerID}', [PostController::class, 'editAnswer'])->name('admin.editAnswer'); // Display the form for editing a specific answer
        Route::post('/admin/discussion/update-answer/{AnswerID}', [PostController::class, 'updateAnswer'])->name('admin.updateAnswer'); // Handle the update of a specific answer
        Route::get('/admin/discussion/delete-answer/{AnswerID}', [PostController::class, 'deleteAnswer'])->name('admin.deleteAnswer'); // Handle the deletion of a specific answer
        Route::get('/admin/discussion/answer-history/{AnswerID}', [PostController::class, 'viewAnswerHistory'])->name('admin.viewAnswerHistory'); // View the history of a specific answer
        Route::get('/admin/discussion/search', [PostController::class, 'search'])->name('admin.search');


        //TEST ACTIONS
        Route::get('/admin/discussion', [PostController::class, 'homepageName'])->name('randomPost'); // Display random posts

        Route::get('/admin/leaderboard', [AdminController::class, 'leaderboard'])->name('admin.leaderboard');

    });

    Route::middleware(['employee'])->group(function () {
        // Routes specific to employee
        Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard'); 
        Route::get('/employee/profile-page', [EmployeeController::class, 'profile_page'])->name('employee.profile_page');
        Route::get('/employee/onboarding-home-page', [ModuleController::class, 'modules'])->name('employee.onboarding-home-page');
        Route::get('/employee/layout', [EmployeeController::class, 'layout'])->name('employee.layout'); 

        Route::get('/employee/discussion', [EmployeePostController::class, 'homepageName'])->name('employee.discussion'); 
        Route::get('/employee/discussion/create-post', [EmployeePostController::class, 'typeOwn'])->name('employee.create-post'); 
        Route::post('/employee/discussion/create-post', [EmployeePostController::class, 'createPost'])->name('employee.createPost'); 
        Route::post('/employee/discussion/submit-answer/{PostID}', [EmployeePostController::class, 'submitAnswer'])->name('employee.submitAnswer');
        Route::get('/employee/check-post', [EmployeePostController::class, 'checkPostedQuestions'])->name('employee.check-post');
        Route::get('/employee/discussion/post/{PostID}', [EmployeePostController::class, 'postDisplay'])->name('employee.postDisplay');
        Route::get('/employee/discussion/delete-post/{PostID}', [EmployeePostController::class, 'deletePost'])->name('employee.deletePost');
        Route::get('/employee/discussion/view-history/{PostID}', [EmployeePostController::class, 'viewHistory'])->name('employee.viewHistory');
        Route::get('/employee/discussion/edit-post/{PostID}', [EmployeePostController::class, 'editPost'])->name('employee.editPost');
        Route::post('/employee/discussion/update-post/{PostID}', [EmployeePostController::class, 'updatePost'])->name('employee.updatePost');
        Route::get('/employee/discussion/edit-answer/{AnswerID}', [EmployeePostController::class, 'editAnswer'])->name('employee.editAnswer');
        Route::post('/employee/discussion/update-answer/{AnswerID}', [EmployeePostController::class, 'updateAnswer'])->name('employee.updateAnswer');
        Route::get('/employee/discussion/delete-answer/{AnswerID}', [EmployeePostController::class, 'deleteAnswer'])->name('employee.deleteAnswer');
        Route::get('/employee/discussion/answer-history/{AnswerID}', [EmployeePostController::class, 'viewAnswerHistory'])->name('employee.viewAnswerHistory');
            Route::post('/employee/update-profile', [EmployeeController::class, 'updateProfile'])->name('employee.update_profile');

        // Route::get('/employee/my-modules', [EmployeeController::class, 'showMyModules'])->name('employee.my_modules');
        // Route::get('/employee/view-module/{id}', [EmployeeController::class, 'viewModule'])->name('employee.view_module');
        Route::get('/employee/my-modules', [EmployeeController::class, 'myModules'])->name('employee.my_modules');

        Route::get('employee/modules/{moduleId}/check-progress', [EmployeeController::class, 'checkItemProgress'])->name('employee.check_item_progress');
        
        
        
        Route::middleware(['ensure.quiz.completed'])->group(function () {
            Route::get('employee/pages/{itemId}', [EmployeeController::class, 'viewPage'])->name('employee.view_page');
        });

        Route::post('employee/mark-completed/{itemId}', [EmployeeController::class, 'markCompleted'])->name('employee.mark_completed');
        // Define the route for the module completion page
        Route::get('employee/module-complete/{moduleId}', [EmployeeController::class, 'moduleComplete'])->name('employee.module_complete');

        Route::post('employee/submit-quiz/{quizId}', [EmployeeController::class, 'submitQuiz'])->name('employee.submit_quiz');

        Route::get('employee/find-colleagues', [EmployeeController::class, 'findColleagues'])->name('employee.find_colleagues');
        Route::get('employee/colleague-details/{id}', [EmployeeController::class, 'colleagueDetails'])->name('employee.colleague_details');

        Route::get('/employee/leaderboard', [EmployeeController::class, 'leaderboard'])->name('employee.leaderboard');
        

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


// //Quiz Routes
// Route::resource('quizzes', QuizController::class);
// Route::get('/employee/onboarding-quiz', [QuizController::class, 'index'])->name('employee.onboarding-quiz');

// Route::get('/onboarding-quiz/create', [QuizController::class, 'create']);
// Route::get('/quizzes/{quiz}/show', [QuizController::class, 'show'])->name('quizzes.show');

// //add by aifei
// Route::post('quizzes/{quiz}/new-attempt', [QuizController::class, 'newAttempt'])->name('quizzes.new-attempt');


// Route::post('/quizzes/{quiz}/submit-answers', [QuizController::class, 'submitAnswers'])->name('quizzes.submit-answers');

// Route::get('/quizzes/{quiz}/details', [QuizController::class, 'getDetails'])->name('quizzes.get-details');

// // Quiz for Admin
// Route::get('/admin/onboarding-quiz', [QuizController::class, 'adminViewQuiz'])->name('admin.onboarding-quiz');
// //Route::get('/quizzes/{quiz}/edit-quiz', [QuizController::class, 'show'])->name('quizzes.show');
// Route::get('quizzes/{quiz}/edit', [QuizController::class, 'editQuiz'])->name('quizzes.edit');
// Route::put('quizzes/{quiz}', [QuizController::class, 'updateQuiz'])->name('quizzes.update');
// // Route::delete('/quizzes/{quiz}', 'QuizController@delete')->name('quizzes.delete');
// Route::delete('/quizzes/{quiz}', [QuizController::class, 'delete'])->name('quizzes.delete');

// //Route::get('/quizzes', [QuizController::class, 'adminViewQuiz'])->name('admin.view-quiz-list');
// Route::get('/view-quiz-list', [QuizController::class, 'viewQuizList'])->name('admin.view-quiz-list');  // Correct route

// // Route for viewing answered employees
// Route::get('/quizzes/{quiz}/answered-employees', [QuizController::class, 'viewAnsweredEmployees'])->name('admin.employee-list');

// // Route for viewing quiz answers (you may need to implement this if not already done)
// Route::get('/quizzes/{quiz}/employee/{employee}/answers', [QuizController::class, 'viewQuizAnswer'])->name('admin.view-quiz-answer');
// Route::post('/quizzes/{quiz}/answers/{employee}', [QuizController::class, 'updateQuizAnswer'])->name('admin.update-quiz-answer');
