<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\TokenVerifiMiddleware;

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
Route::POST('/register', [UsersController::class, 'registration']);
Route::POST('/login', [UsersController::class, 'loginUser']);
Route::POST('/sendotp', [UsersController::class, 'sendOTP']);
Route::POST('/otpverify', [UsersController::class, 'verifyOtp']);
Route::POST('/restpassword', [UsersController::class, 'resetPassword'])
->middleware([TokenVerifiMiddleware::class]);


