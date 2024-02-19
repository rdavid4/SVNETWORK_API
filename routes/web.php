<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\VerificationController;

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
Route::get('reset-password', [ResetPasswordController::class, 'verify'])->name('auth.reset-password')->middleware('signed');
