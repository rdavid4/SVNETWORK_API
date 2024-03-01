<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\VerificationController;
use App\Models\Company;
use App\Notifications\UserVerification;
use App\Models\User;
use App\Notifications\CompanyCreatedNotification;
use App\Notifications\CompanyVerifiedNotification;
use App\Notifications\UserCreatedNotification;

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
    return view('welcome');
});

Route::get('register/verification', [VerificationController::class, 'verifyEmail'])->name('auth.verify')->middleware('signed');
Route::post('reset-password', [ResetPasswordController::class, 'verify'])->name('auth.change-password')->middleware('signed');

Route::get('/notification', function () {
    $user = Company::first();
    $user->link = 'localhost:8000/asdasdadsdadasd.com';
    return (new CompanyCreatedNotification($user))
                ->toMail($user);
});
