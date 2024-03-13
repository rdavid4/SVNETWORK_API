<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\GeoipController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\RenewPasswordController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionTypeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZipcodeController;
use App\Models\QuestionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// AUTH REGISTER + LOGIN
Route::get('/auth/user', [UserController::class, 'show'])->middleware('auth:sanctum');
Route::get('/auth/user/check-email/{email}', [UserController::class, 'emailExist']);
Route::post('/auth/register/company', [RegisterController::class, 'registerCompany']);
Route::post('/auth/register', [RegisterController::class, 'register']);
Route::post('/auth/register/google', [RegisterController::class, 'registerGoogle']);
Route::post('/auth/login', [LoginController::class, 'login']);
Route::post('/auth/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/auth/register/verification', [VerificationController::class, 'verifyEmail'])->middleware('signed');
Route::post('/auth/email/verify/resend/{email}', [VerificationController::class, 'resend']);
Route::post('/auth/renew-password/send', [RenewPasswordController::class, 'send']);

// USER PRIVATE
Route::post('/user/password', [UserController::class, 'updatePassword'])->middleware('auth:sanctum');

//SYSTEM DATA

Route::get('/system/geoip/{ip?}', [GeoipController::class, 'show']);
Route::get('/system/states/{iso}', [StateController::class, 'show']);
Route::get('/system/states', [StateController::class, 'list']);
Route::get('/system/categories', [CategoryController::class, 'list']);
Route::post('/system/categories', [CategoryController::class, 'store']);
Route::post('/system/services', [ServiceController::class, 'store']);
Route::get('/system/services', [ServiceController::class, 'list']);
Route::get('/system/services/{service}', [ServiceController::class, 'showPublic']);
Route::get('/system/zipcode/{zipcode}', [ZipcodeController::class, 'show']);
Route::get('/system/zipcode', [ZipcodeController::class, 'list']);

Route::post('/payments/checkout', [PaymentController::class, 'checkout']);
Route::post('/payments/customer', [PaymentController::class, 'payment']);
Route::get('/customer', [PaymentController::class, 'getCustomer']);

Route::get('/companies/{slug}', [CompanyController::class, 'showBySlug']);
Route::post('/companies', [CompanyController::class, 'storeFromRegister']);
Route::get('/user/companies', [UserController::class, 'company'])->middleware('auth:sanctum');
//DASHBOARD ADMIN
Route::post('/admin/companies/{company}/logo', [CompanyController::class, 'storeLogo']);
Route::post('/admin/companies', [CompanyController::class, 'store']);
Route::post('/admin/companies/verify', [CompanyController::class, 'verify']);
Route::post('/admin/companies/user', [CompanyController::class, 'addUser']);
Route::put('/admin/companies/{company}', [CompanyController::class, 'update']);
Route::delete('/admin/companies/{company}', [CompanyController::class, 'destroy']);
Route::get('/admin/companies/unverified', [CompanyController::class, 'listUnverified']);
Route::get('/admin/companies', [CompanyController::class, 'list']);
Route::get('/admin/companies/{company}', [CompanyController::class, 'show']);
Route::post('/admin/users/{user}/image', [UserController::class, 'storeImage']);
Route::get('/admin/users/pro', [UserController::class, 'listPro']);
Route::get('/admin/users', [UserController::class, 'list']);
Route::post('/admin/users', [UserController::class, 'store']);
Route::post('/admin/services', [ServiceController::class, 'store']);
Route::get('/admin/services/prices', [ServiceController::class, 'prices']);
Route::put('/admin/services/{service}', [ServiceController::class, 'update']);
Route::get('/admin/services/{service}', [ServiceController::class, 'show']);
Route::post('/admin/questions', [QuestionController::class, 'store']);
Route::delete('/admin/questions/{question}', [QuestionController::class, 'destroy']);
Route::get('/admin/question-types', [QuestionTypeController::class, 'list']);
Route::post('/admin/answers', [AnswerController::class, 'store']);
Route::post('/admin/categories', [CategoryController::class, 'store']);
Route::get('/admin/user/check-email/{email}', [UserController::class, 'AdminEmailExist']);
