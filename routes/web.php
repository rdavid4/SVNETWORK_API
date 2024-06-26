<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\VerificationController;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\Matches;
use App\Models\Service;
use App\Notifications\UserVerification;
use App\Models\User;
use App\Notifications\CompanyCreatedNotification;
use App\Notifications\CompanyVerifiedNotification;
use App\Notifications\MatchesUserNotification;
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
Route::get('share/companies/{slug}', [ShareController::class, 'companies']);

Route::get('register/verification', [VerificationController::class, 'verifyEmail'])->name('auth.verify')->middleware('signed');
Route::post('reset-password', [ResetPasswordController::class, 'verify'])->name('auth.change-password')->middleware('signed');

Route::get('/notification', function () {
    $user = User::where('email', 'rogerdavid444@gmail.com')->first();
    $user->link = 'localhost:8000/asdasdadsdadasd.com';
    $match = Matches::all();
    $service = Service::find(1);
    $matches = $service->companyServiceZip
    ->where('zipcode_id', 16281)
    ->take(3);
    $matches = $matches->map(function($match) use($user,$service){
        return new CompanyResource($match->company);
    });

    $matches = Company::all()->take(3);

    $data = ['matches' => $matches, 'service'=>$service];
    //  $user->notify(new MatchesUserNotification($matches));
    return view('mail.invoice.paid', ['matches' => $data]);
});
