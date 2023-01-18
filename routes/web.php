<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\LoginController;
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
    return view('welcome');
});
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

Route::get('/',[HomeController::class,'index']);
Route::get('/home',[HomeController::class,'index']);
Route::get('/login',[LoginController::class,'login'])->name('login');
Route::post('/login-process',[LoginController::class,'process_login'])->name('login-process');
Route::post('/signup-process',[LoginController::class,'process_signup'])->name('signup-process');
Route::get('/logout-process',[LoginController::class,'process_logout'])->name('logout-process');
Route::get('/about', function () {
    return view('web.pages.about');
});
Route::get('/product', function () {
    return view('web.pages.product');
});

Route::get('/why-us', function () {
    return view('web.pages.why-us');
});

Route::get('/testimonials', function () {
    return view('web.pages.testimonials');
});




