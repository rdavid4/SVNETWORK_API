<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AnswerProjectController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\GeoipController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\RenewPasswordController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionTypeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZipcodeController;
use App\Models\AnswerProject;
use App\Models\QuestionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Stripe\SearchResult;

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
Route::post('/check/robot', [UserController::class, 'checkRobot']);
Route::post('/auth/register/company', [RegisterController::class, 'registerCompany']);
Route::post('/auth/register', [RegisterController::class, 'register']);
Route::post('/auth/register-guess', [UserController::class, 'storeGuess']);
Route::post('/auth/register/google', [RegisterController::class, 'registerGoogle']);
Route::post('/auth/login', [LoginController::class, 'login']);
Route::post('/auth/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/auth/register/verification', [VerificationController::class, 'verifyEmail'])->middleware('signed');
Route::post('/auth/email/verify/resend/{email}', [VerificationController::class, 'resend']);
Route::post('/auth/renew-password/send', [RenewPasswordController::class, 'send']);

// USER PRIVATE
Route::post('/user/password', [UserController::class, 'updatePassword'])->middleware('auth:sanctum');
Route::get('/user/companies', [UserController::class, 'company'])->middleware('auth:sanctum');
Route::get('/user/projects/{project}', [UserController::class, 'showProject']);
Route::get('/user/projects', [UserController::class, 'projects']);
Route::post('/user/image', [UserController::class, 'storeImageAuthUser'])->middleware('auth:sanctum');
Route::put('/user', [UserController::class, 'update'])->middleware('auth:sanctum');
//SYSTEM DATA

Route::get('/system/geoip/{ip?}', [GeoipController::class, 'show']);
Route::get('/system/states/{iso}', [StateController::class, 'show']);
Route::get('/system/states', [StateController::class, 'list']);
Route::get('/system/categories', [CategoryController::class, 'list']);
Route::post('/system/categories', [CategoryController::class, 'store']);
Route::post('/system/services', [ServiceController::class, 'store']);
Route::get('/system/services', [ServiceController::class, 'list']);
Route::get('/system/services/top10', [ServiceController::class, 'top10']);
Route::get('/system/services/{service}', [ServiceController::class, 'showPublic']);
Route::get('/system/zipcode/{zipcode}', [ZipcodeController::class, 'show']);
Route::get('/system/zipcode', [ZipcodeController::class, 'list']);

//SEARCH
Route::post('/search', [SearchController::class, 'search']);

//PAYMENTS
Route::get('/payments/methods/{setup}', [PaymentController::class, 'addPaymentMethod'])->middleware('auth:sanctum');
Route::get('/payments/retrieve/session/{id}', [PaymentController::class, 'retrieveSession'])->middleware('auth:sanctum');
Route::get('/payments/retrieve/setup/{id}', [PaymentController::class, 'retrieveIntent'])->middleware('auth:sanctum');
Route::post('/payments/checkout', [PaymentController::class, 'checkout'])->middleware('auth:sanctum');
Route::post('/payments/custom', [PaymentController::class, 'customCard'])->middleware('auth:sanctum');
Route::post('/payments/customer', [PaymentController::class, 'payment'])->middleware('auth:sanctum');
Route::post('/payments-methods/card', [PaymentMethodController::class, 'storeCard'])->middleware('auth:sanctum');
Route::delete('/payments-methods/card/{id}', [PaymentMethodController::class, 'deleteCard'])->middleware('auth:sanctum');
Route::get('/payments/methods', [PaymentController::class, 'getMethodCard'])->middleware('auth:sanctum');
Route::get('/customer', [PaymentController::class, 'getCustomer'])->middleware('auth:sanctum');
Route::get('/user/payments', [PaymentController::class, 'getCharges'])->middleware('auth:sanctum');
Route::get('/user/payments/week', [PaymentController::class, 'totalWeek'])->middleware('auth:sanctum');


//COMPANIES
Route::get('/companies/{slug}', [CompanyController::class, 'showBySlug']);
Route::get('/companies/config/{company}', [CompanyController::class, 'getConfiguration'])->middleware('auth:sanctum');
Route::post('/companies/services/remove', [CompanyController::class, 'destroyService'])->middleware('auth:sanctum');
Route::post('/companies/services', [CompanyController::class, 'addService'])->middleware('auth:sanctum');
Route::post('/companies/services/zipcodes', [ServiceController::class, 'zipcodesByRegion'])->middleware('auth:sanctum');
Route::post('/companies/services/states', [CompanyController::class, 'storeStates'])->middleware('auth:sanctum');
Route::post('/companies/services/zipcodes/update', [ServiceController::class, 'updateZipcodes'])->middleware('auth:sanctum');
Route::post('/companies/services/zipcodes/all', [ServiceController::class, 'selectAllState'])->middleware('auth:sanctum');
Route::post('/companies/services/zipcodes/remove', [ServiceController::class, 'removeAllState'])->middleware('auth:sanctum');
Route::post('/companies/services/pause', [ServiceController::class, 'pause'])->middleware('auth:sanctum');
Route::get('/companies/services/{slug}/{company_id}', [CompanyController::class, 'getService'])->middleware('auth:sanctum');
Route::get('/companies/{company}/projects', [CompanyController::class, 'projects']);
Route::post('/companies', [CompanyController::class, 'storeFromRegister']);
Route::put('/companies/{company}', [CompanyController::class, 'update']);
Route::post('/companies/{company}/logo', [CompanyController::class, 'storeLogo']);
Route::post('/companies/{company}/images', [CompanyController::class, 'storeImages']);
Route::delete('/companies/images/{image}', [CompanyController::class, 'deleteImage']);
Route::post('/companies/{company}/video', [CompanyController::class, 'storeVideo']);
Route::get('/companies/{company}/reviews', [CompanyController::class, 'reviews'])->middleware('auth:sanctum');

//PROJECTS
Route::post('/projects/images', [ProjectController::class, 'storeImage']);
Route::post('/projects', [ProjectController::class, 'store']);
Route::get('/projects/{project}', [ProjectController::class, 'show']);

//ANSWERS
Route::post('/answers', [AnswerProjectController::class, 'store'])->middleware('auth:sanctum');


//REVIEWS
Route::post('/reviews', [ReviewController::class, 'store'])->middleware('auth:sanctum');
Route::post('/reviews/reply', [ReviewController::class, 'reply'])->middleware('auth:sanctum');
Route::put('/reviews/reply/{reviewReplay}', [ReviewController::class, 'updateReply'])->middleware('auth:sanctum');
Route::put('/reviews/{review}', [ReviewController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/reviews/{review}', [ReviewController::class, 'delete'])->middleware('auth:sanctum');
Route::get('/reviews', [ReviewController::class, 'list']);
Route::post('/reviews/{review}/report', [ReviewController::class, 'report'])->middleware('auth:sanctum');

//DASHBOARD ADMIN
Route::post('/admin/companies/{company}/logo', [CompanyController::class, 'storeLogoAdmin']);
Route::post('/admin/companies', [CompanyController::class, 'store']);
Route::post('/admin/companies/verify', [CompanyController::class, 'verify']);
Route::post('/admin/companies/user', [CompanyController::class, 'addUser']);
Route::put('/admin/companies/{company}', [CompanyController::class, 'updateAdmin']);
Route::delete('/admin/companies/{company}', [CompanyController::class, 'destroy']);
Route::get('/admin/companies/unverified', [CompanyController::class, 'listUnverified']);
Route::get('/admin/companies', [CompanyController::class, 'list']);
Route::get('/admin/companies/{company}', [CompanyController::class, 'show']);
Route::post('/admin/users/{user}/image', [UserController::class, 'storeImage']);
Route::post('/admin/companies/{company}/video', [CompanyController::class, 'storeVideo']);
Route::get('/admin/users/pro', [UserController::class, 'listPro']);
Route::get('/admin/users', [UserController::class, 'list']);
Route::post('/admin/users', [UserController::class, 'store']);
Route::post('/admin/services/add', [ServiceController::class, 'addService']);
Route::delete('/admin/services/remove', [ServiceController::class, 'removeService']);
Route::post('/admin/services', [ServiceController::class, 'store']);
Route::post('/admin/services/{service}/images', [ServiceController::class, 'storeImage']);
Route::delete('/admin/services/{service}', [ServiceController::class, 'destroy']);
Route::get('/admin/services/prices', [ServiceController::class, 'prices']);
Route::put('/admin/services/{service}', [ServiceController::class, 'update']);
Route::get('/admin/services/{service}', [ServiceController::class, 'show']);
Route::post('/admin/questions', [QuestionController::class, 'store']);
Route::delete('/admin/questions/{question}', [QuestionController::class, 'destroy']);
Route::get('/admin/question-types', [QuestionTypeController::class, 'list']);
Route::get('/admin/matches', [SearchController::class, 'matchesList']);
Route::get('/admin/nomatches', [SearchController::class, 'noMatchesList']);
Route::put('/admin/nomatches/done/{noMatches}', [SearchController::class, 'updateNoMatches']);
Route::post('/admin/answers', [AnswerController::class, 'store']);
Route::delete('/admin/answers/{answer}', [AnswerController::class, 'destroy']);
Route::post('/admin/categories', [CategoryController::class, 'store']);
Route::get('/admin/user/check-email/{email}', [UserController::class, 'AdminEmailExist']);

Route::get('/admin/dashboard/users', [DashboardController::class, 'registeredUsers']);
Route::get('/admin/dashboard/companies', [DashboardController::class, 'registeredCompanies']);
Route::get('/admin/dashboard/matches', [DashboardController::class, 'totalMatches']);
Route::get('/admin/dashboard/nomatches', [DashboardController::class, 'totalNomatches']);
Route::get('/admin/dashboard/services', [DashboardController::class, 'topServices']);
Route::get('/admin/dashboard/stats', [DashboardController::class, 'getStats'])->middleware('auth:sanctum');

Route::get('/admin/companies/services/{slug}/{company_id}', [CompanyController::class, 'getService'])->middleware('auth:sanctum');
Route::post('/admin/companies/services/states', [CompanyController::class, 'storeStates'])->middleware('auth:sanctum');
Route::post('/admin/companies/services/zipcodes', [ServiceController::class, 'zipcodesByRegion'])->middleware('auth:sanctum');
Route::get('/admin/payments/all-charges', [PaymentController::class, 'getAllCharges'])->middleware('auth:sanctum');
Route::get('/admin/payments/balance', [PaymentController::class, 'getBalance'])->middleware('auth:sanctum');
Route::post('/admin/payments/recharge', [PaymentController::class, 'recharge'])->middleware('auth:sanctum');
Route::post('/admin/send-lead', [SearchController::class, 'sendLead'])->middleware('auth:sanctum');
Route::get('/admin/transactions', [TransactionController::class, 'list'])->middleware('auth:sanctum');

