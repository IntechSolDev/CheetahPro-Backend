<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PushNotificationController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\SubServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProviderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\AdminController;
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

// Admin Login and Registration Here
    Route::namespace('auth')->group(function () {
    Route::get('/login',[LoginController::class,'show_login_form'])->name('admin.showlogin');
    Route::post('/login',[LoginController::class,'process_login'])->name('admin.storelogin');
    Route::get('/register',[LoginController::class,'show_signup_form'])->name('admin.showregister');
    Route::post('/register',[LoginController::class,'process_signup'])->name('admin.storeregister');
    Route::post('/logout',[LoginController::class,'logout'])->name('admin.logout');
    Route::get('reset_password', [LoginController::class,'passwordResetForm'])->name('admin.reset-form');
    Route::post('reset_password_forgot', [LoginController::class,'forgot'])->name('admin.forgot');
    Route::get('confirm-form', [LoginController::class,'confirmForm'])->name('admin.confirm-form');
    Route::post('reset_password_confirm', [LoginController::class,'reset'])->name('admin.pass.code');
     });

// Admin Protected Route Here
  Route::group(['middleware' => ['admin']], function () {
    Route::get('/', [AdminController::class,'index']);
    Route::resource('push-notification', PushNotificationController::class);
    Route::post('send-notification', [PushNotificationController::class,'sendNotification'])->name('send.notification');

    //User
    Route::resource('user', UserController::class);

    //Provider
    Route::resource('provider', ProviderController::class);

    //Service
    Route::resource('service', ServiceController::class);
    Route::resource('sub-service', SubServiceController::class);

    //Product
    Route::resource('product', ProductController::class);

    //Order
    Route::resource('order', OrderController::class);
    Route::get('invoice/{id?}',[OrderController::class,'getInvoice'])->name('invoice');

    //Subscription
    Route::resource('subscription', SubscriptionPlanController::class);
    Route::get('set-trial',[SubscriptionPlanController::class,'getTrial'])->name('subscription.trial');
    Route::post('create-trial',[SubscriptionPlanController::class,'createTrial'])->name('subscription.trial.store');

    });
