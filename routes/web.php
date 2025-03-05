<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CROController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home/home-view');
});

Route::get('/home', function () {
    return view('home/home-view');
});

Route::get('/otp-mail', function(){
    return view('home/otp-mail');
});

// Route::get('/student-view/?userList=', function(){
//     return view('home/student-details');
// });

// Route::get('/send-test-email', function () {
//     Mail::raw('This is a test email from Laravel.', function ($message) {
//         $message->to('itcoordinator@aaft.com')
//                 ->subject('Test Email');
//     });
//     return 'Test email sent successfully!';
// });

Route::get('logout', [HomeController::class, 'Logout']);

Route::get('verify-email', [HomeController::class, 'Login']);
Route::get('resend-otp', [HomeController::class, 'ResendOtp']);
Route::get('submit-otp', [HomeController::class, 'SubmitOtp']);

Route::get('student-details', [HomeController::class, 'StudentDetails']);
Route::get('cro-details', [CROController::class, 'CRODetails']);
Route::get('admin-details', [HomeController::class, 'AdminDetails']);
Route::get('it-details', [HomeController::class, 'ITDetails']);

Route::get('store-placement/{entity}/{school}/{yes}/{no}/{course}', [HomeController::class, 'SubmitPlacement']);
Route::get('submit-questionarie', [HomeController::class, 'SubmitQuestionarie']);
Route::get('get-city', [HomeController::class, 'GetCity']);
Route::get('thankYou', [HomeController::class, 'ThankYou']);

Route::get('student-upload', [CROController::class, 'BulkUpload']);
Route::get('image-upload', [CROController::class, 'BulkImageUpload']);
Route::POST('save-image', [CROController::class, 'SaveImage']);

Route::get('get-school', [CROController::class, 'GetSchool']);
Route::get('get-course', [CROController::class, 'GetCourse']);
Route::get('view-student-details', [CROController::class, 'ViewStudentDetails']);
Route::get('download-student-details/{entity_id?}/{school_id?}/{course_id?}/{optin?}', [CROController::class, 'DownloadStudentDetails']);
Route::post('/upload.student.data', [CROController::class, 'UploadStudent'])->name('upload.student.data');
Route::get('download-student-data-template', [CROController::class, 'DownloadStudentTemplate']);
Route::get('view-company', [CROController::class, 'ViewCompany']);
Route::get('autocomplete-search', [CROController::class, 'AutoCompleteCompany']);
Route::get('get-company-details', [CROController::class, 'CheckCompany']);
Route::get('create-company', [CROController::class, 'AddCompany']);
Route::get('create-company-lead', [CROController::class, 'AddCompanyLead']);
Route::get('company-report', [CROController::class, 'CompanyReports']);
Route::get('view-company-details', [CROController::class, 'CompanyReportDetails']);
Route::get('download-company-details/{entity_id?}/{school_id?}/{course_id?}', [CROController::class, 'DownloadCompanyDetails']);




