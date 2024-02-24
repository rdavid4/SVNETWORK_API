<?php
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\GeoipController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\RenewPasswordController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
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
Route::get('/auth/user/check-email/{email}', [UserController::class, 'emailExist']);
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
Route::get('/system/geoip/zipcode/{zipcode?}', [GeoipController::class, 'zipcode']);
Route::get('/system/geoip/{ip?}', [GeoipController::class, 'show']);
Route::post('/payments/checkout', [PaymentController::class, 'checkout']);
Route::post('/payments/customer', [PaymentController::class, 'payment']);
Route::get('/customer', [PaymentController::class, 'getCustomer']);

//DASHBOARD ADMIN
Route::post('/admin/companies', [CompanyController::class, 'store']);
Route::get('/admin/companies/{id}', [CompanyController::class, 'show']);
Route::put('/admin/companies/{id}', [CompanyController::class, 'update']);
Route::delete('/admin/companies/{id}', [CompanyController::class, 'destroy']);
Route::get('/admin/companies', [CompanyController::class, 'list']);
