<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\UserSideController;
use App\Http\Controllers\Api\StripePaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

//User Auth Api Route
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::post('forgot', [UserController::class, 'forgot']);
Route::post('confirm-code', [UserController::class, 'confirmCode']);
Route::post('reset', [UserController::class, 'reset']);
Route::post('change-password', [UserController::class, 'changePassword']); //Bear Token Needed
Route::post('verify', [UserController::class, 'verifyEmail']);
Route::get('details', [UserController::class, 'details']); //Bear Token Needed
Route::get('delete-user', [UserController::class, 'delete']); //Delete All user
Route::post('update-fcm', [UserController::class, 'updateFcmToken']);
Route::get('get-main-service', [ServiceController::class, 'getMainService']); //Get Main Service

Route::group(['middleware' =>'auth:api'], function () {

    //Notification List
    Route::get('notification-list', [NotificationController::class, 'viewAllNotification']);
    Route::post('notification-status', [NotificationController::class, 'changeStatusNotification']);
    Route::post('notification-delete', [NotificationController::class, 'deleteNotification']);

    //----------------------------------------User--------------------------------------------------------
    
    //Route::get('profile/{id}', [UserController::class, 'viewProfile']); //View User Profile
    Route::post('edit', [UserController::class, 'edit']);
    Route::post('delete-user', [UserController::class, 'deleteUser']); //User deleted
    Route::post('edit-user-services', [UserController::class, 'editUserService']); //Create and Edit services
    
    //Home
    Route::get('user-home/{post_code?}', [UserSideController::class, 'userHome']); // User Home data -- Cheetha 1.1 version
    Route::get('view-provider-postal/{post_code?}', [ServiceController::class, 'providerByPostal']); // All Provider data -- Cheetha 1.1 version
    
    //Booking
    Route::post('create-booking', [BookingController::class, 'addUserBooking']); //Add Booking
    Route::get('view-booking-history', [BookingController::class, 'viewBookingHistory']); //View User Booking
    Route::get('view-booking-detail/{id}', [BookingController::class, 'viewBookingDetail']); //View Booking Detail
    Route::post('cancel-booking', [BookingController::class, 'cancelBooking']); //Cancel Booking Detail
    Route::post('update-user-booking-status', [BookingController::class, 'updateUserBookingStatus']); //Update User Booking Status
   
    //Follow Following
    Route::post('follow', [FollowController::class, 'follow']); //Follow tap
    Route::get('follow-count', [FollowController::class, 'countFollow']); //Follower and Following count
    Route::get('follower', [FollowController::class, 'getListfollower']); //Get List Follower
    Route::get('following', [FollowController::class, 'getListfollowing']); //Get List Following

    //Services
    Route::get('get-sub-service/{id}', [ServiceController::class, 'getSubService']); //Get All Sub Services -- Cheetha 1.1 version
    Route::get('get-best-offer', [ServiceController::class, 'getBestOffer']); //Get Best offer Service
    Route::get('get-user-by-service/{id}', [ServiceController::class, 'getUserByService']); //Get Users by specific sub service
    Route::get('get-all-popular-service', [ServiceController::class, 'getAllPopularService']); //Get All Popular Service
    Route::get('get-provider-by-subservice/{id}/{option?}/{search?}', [ServiceController::class, 'getProviderBySubService']); //Get Provider by sub specific sub service
    Route::post('add-service-review', [ServiceController::class, 'addServiceReview']); //Add service Review
    Route::post('gallery-image-upload', [ServiceController::class, 'uploadImage']); //Add Images
    Route::get('all-reviews/{id}', [ServiceController::class, 'allReviews']); //Get All Reviews

    //Order
    Route::post('create-order', [OrderController::class, 'createOrder']); // Order Create
    Route::post('cancel-order', [OrderController::class, 'cancelOrder']); // Order Cancel
    Route::post('change-order-date', [OrderController::class, 'changeOrderDate']); // Order Cancel

    //-------------------------------------Provider----------------------------------------------------------
    
    //Profile
    Route::get('view-provider-profile/{id}', [ProviderController::class, 'viewProviderProfile']); // Provider Profile -- Cheetha 1.1 version
    Route::get('view-provider-reviews/{id}', [ProviderController::class, 'viewProviderReviews']); // Provider Reviews -- Cheetha 1.1 version
    Route::get('provider-booking-new', [BookingController::class, 'providerBookingNew']); //Provider Booking New
    Route::get('provider-booking-history', [BookingController::class, 'providerBookingHistory']); //Provider Booking Status
    Route::post('provider-booking-status-update', [BookingController::class, 'updateBookingStatus']); //Provider Update Booking Status

    //Service Update
    Route::post('edit-provider-service', [UserController::class, 'editProviderService']); //Update Sub Service 
      
    //Order
    Route::get('view-order-list', [OrderController::class, 'viewOrderList']); // Order List
    Route::get('view-order-list-provider/{filterby?}', [OrderController::class, 'viewOrderListprovider']); // Order List Provider
    Route::get('view-order-detail/{id}', [OrderController::class, 'viewOrderDetail']); // Order Detail by order_id
    Route::post('update-order-status', [OrderController::class, 'updateOrderStatus']); // Update Order Status
    Route::post('update-order', [OrderController::class, 'updateOrder']); // Update Order


    //Subscription Payment
    Route::post('stripe-payment',[StripePaymentController::class, 'process']); // Recursive Payment
    Route::post('payment-status',[StripePaymentController::class, 'subscribe_status']); // Check Recursive Payment Status
    Route::post('trial-payment-status',[StripePaymentController::class, 'checkTrial']);  // Check Trial Payment Status
    Route::get('list-subscription-plan',[StripePaymentController::class, 'getAllSubscription']);
    Route::post('user-trial',[StripePaymentController::class, 'trial']); //Start trial


    // express,account links and payment methods
    Route::post('create-express', [PaymentController::class,'createExpress']);
    Route::post('account-link', [PaymentController::class,'accountLinks']);
    Route::post('payment-intent', [PaymentController::class,'paymentIntent']);
    Route::post('wallet', [PaymentController::class,'wallet']);
    Route::post('stripe-status', [PaymentController::class,'stripeStatusApproved']);
    Route::post('stripe-status-disapprove', [PaymentController::class,'stripeStatusDisapproved']);
    Route::post('check-balance', [PaymentController::class,'stripeBalance']);
    Route::post('transfer', [PaymentController::class,'stripeTransfer']);
    Route::post('delete-connect', [PaymentController::class,'deleteAccount']);
    Route::post('customer', [PaymentController::class,'getCustomer']);
    Route::post('bank-list', [PaymentController::class,'bankList']);
    Route::post('bank-transfer', [PaymentController::class,'bankTransfer']);
    Route::get('transaction-list', [PaymentController::class,'transactionlist']);

});

