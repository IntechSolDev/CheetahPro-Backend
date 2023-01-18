<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserBookingDetailResource;
use App\Http\Resources\UserBookingListResource;
use App\Models\Booking;
use App\Models\NotificationUser;
use App\Models\UserBookingService;
use App\Models\UserService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public $success = 200;
    public $error = 404;
    public $validate_error = 401;



//User Side

    //Add User Booking
    public function addUserBooking(Request $request)
    {
        try {
            $user = Auth::user();
            $booking_data = [];
            $user_booking_cost = 0;
            $validator = Validator::make($request->all(), [
                'sub_service' => 'required',
                'provider_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['Service required' => $validator->errors()]);
            }
            $sub_service = $request->sub_service;
            $booking_data['user_id'] = $user->id;
            $booking_data['booking_uuid'] = 'BK' . rand(10000, 99999) . 'U' . $user->id;
            $booking_data['provider_id'] = $request->provider_id;
            $booking_data['address'] = $request->address;
            $booking_data['booking_date'] = $request->booking_date;
            $booking_data['booking_time'] = $request->time;
            $booking_data['note'] = $request->note ?  $request->note : null;
            $booking = Booking::create($booking_data);
            if ($sub_service) {
                foreach ($sub_service as $sub_serv) {
                    $user_service = UserService::where([['user_id', $request->provider_id], ['sub_service_id', $sub_serv]])->first();
                    $booking_data['amount'] = $user_service->service_charges;
                    if ($user_service) {
                        $user_booking_service['booking_id'] = $booking->id;
                        $user_booking_service['user_id'] = $user->id;
                        $user_booking_service['provider_id'] = $request->provider_id;
                        $user_booking_service['main_service_id'] = $user_service->main_service_id;
                        $user_booking_service['sub_service_id'] = $sub_serv;
                        $user_booking_service['service_charges'] = $user_service->service_charges;
                        $booking_detail = UserBookingService::create($user_booking_service);
                        $user_booking_cost = $user_booking_cost + $user_service->service_charges;
                    }
                }
            }
            $booking_data['total_amount'] = $user_booking_cost;
            $booking = $booking::updateOrCreate(['id' => $booking->id], $booking_data);
            if ($booking) {
                //  $orderdata = new Order();
                //  $maildata =  $orderdata->getmaildata($order->id);
                //   \Mail::to(env('MAIL_ORDER_ADDRESS'))->send(new \App\Mail\Invoice($maildata));
                // FireBase
                $message = "Congrats! New Booking Place";
                $data_array = ['title' => 'Booking Placed', 'body' => $message, 'type' => 'booking_placed', 'user' => $user, 'booking_id' => $booking->id, 'description' => $message];
                $user->sendNotification($request->provider_id, $data_array, $message);
                $notify = NotificationUser::create(['user_id' => $user->id, 'send_to' => $request->provider_id, 'message' => $message, 'type' => "booking_placed", 'redirect' => $booking->id]);
                // EndFireBase
                return response()->json(['message' => "Your Booking is created Successfully", 'booking_id' => $booking->id, 'status' => 'success'], $this->success);
            } else {
                return response()->json(['message' => 'Booking not Placed', 'status' => 'error'], $this->error);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'error'], $this->error);
        }
    }
    
    //View User Booking History
    public function viewBookingHistory()
    {
          try {
        $user = Auth::user();
        $user_booking = Booking::where('user_id',$user->id)->latest()->get();
        $list_booking = UserBookingListResource::collection($user_booking);
        if($user_booking->isNotEmpty())
        {
            return response()->json([
                'bookings'=>$list_booking,
                'message' => 'All Previous Booking of User',
                'status' => 'success',
            ], $this->success);
        }
        else
        {
            return response()->json([ 'bookings'=>[],'error' => "No data Found", 'status' => 'success'],$this->success);
        }
          } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'error'], $this->error);
        }
    }

    //View User Booking Detail
    public function viewBookingDetail($id)
    {
        $user_booking = Booking::with(['user','provider'])->where('id',$id)->first();
        $booking_detail = UserBookingDetailResource::make($user_booking);
         if($user_booking)
        {
            return response()->json([
                'booking_detail'=>$booking_detail,
                'message' => 'Get all detail of a Booking',
                'status' => 'success',
            ], $this->success);
        }
        else
        {
            return response()->json([ 'booking_detail'=>[],'error' => "No data Found", 'status' => 'error'],$this->error);
        }
    }
    
    //Cancel User Booking
    public function cancelBooking(Request $request)
    {
         try {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'booking_id'=>'required',
            'booking_status'=>'required'
        ]);
        if($validator->fails()){
            return response()->json(['Field required' => $validator->errors()]);
        }
        
       $booking_total_cost = 0;
       $booking_data =[];
       $booking_detail_data =[];
       $status = $request->booking_status;
       $booking_id = $request->booking_id;
       $booking = Booking::find($booking_id);
       $provider  = User::find($booking->provider_id);
       
       if(!$booking)
        {
            return response()->json(['message' => 'Booking not Found', 'status' => 'error'], $this->error);
        }
        if($booking->user_id != $user->id || $booking->provider_id != $booking->provider_id)
        {
              return response()->json(['message' => 'You have no rights to update the status', 'status' => 'error'], $this->error);
        }
       
   if ($status == 'cancelled')
   {
            $booking_update = $booking->update(['booking_status' => $status, 'rejected' => 1]);
            if ($booking_update) {
                $booking_data = Booking::find($booking->id);
                // FireBase
                $message = "Your booking has been cacnceled";
                $data_array = ['title' => 'Booking Cancelled', 'body' => $message, 'booking_id'=>$booking->id ,'type' => 'booking_cancelled', 'description' => $message];
                $response = $provider->sendNotification($provider->id, $data_array, $message);
                NotificationUser::create(['user_id' => $user->id, 'send_to' => $provider->id, 'booking_uuid' => $booking_data->booking_id,'booking_id' => $booking_data->id, 'message' => $message, 'type' => "booking_cancelled", 'redirect' => $booking_data->id]);
                // EndFireBase
                return response()->json([
                     'booking_data'=>$booking_data,
                    'message' => 'Booking status Updated.',
                    'status' => 'success',
                ], $this->success);
            }
    } 
        
    else {
            return response()->json([
                'data' => ['data' => []],
                'message' => 'Booking status not Updated.',
                'status' => 'error',
            ], $this->error);
        }
         } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'error'], $this->error);
        }
    }
    
    
    
    
    // User Booking Status Update
    public function updateUserBookingStatus(Request $request)
    {
        try {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'booking_id'=>'required',
            'booking_status'=>'required',
        ]);
        if($validator->fails()){
            return response()->json(['Field required' => $validator->errors()]);
        }
        
       $booking_total_cost = 0;
       $booking_data =[];
       $booking_detail_data =[];
       $status = $request->booking_status;
       $booking_id = $request->booking_id;
      
       $booking = Booking::find($booking_id);
       $customer = User::find($booking->user_id);
       
        if(!$booking)
        {
            return response()->json(['message' => 'Booking not Found', 'status' => 'error'], $this->error);
        }
        if($booking->user_id != $user->id)
        {
              return response()->json(['message' => 'You have no rights to update the status', 'status' => 'error'], $this->error);
        }
       
       
       if ($status == 'completed') 
        {
            $booking_update = $booking->update(['booking_status' => $status, 'completed' => 1]);
            if ($booking_update) {
                $booking_data = Booking::find($booking->id);
                // FireBase
                $message = "Booking completed";
                $data_array = ['title' => 'Booking Completed', 'body' => $message,'booking_id'=>$booking->id , 'type' => 'booking_completed', 'description' => $message];
                $response = $customer->sendNotification($booking_data->user_id, $data_array, $message);
                NotificationUser::create(['user_id' => $user->id, 'send_to' => $booking_data->user_id, 'booking_uuid' => $booking_data->booking_id,'booking_id' => $booking_data->id, 'message' => $message, 'type' => "booking_completed", 'redirect' => $booking_data->id]);
                // EndFireBase
                return response()->json([
                    'booking_data'=>$booking_data,
                    'message' => 'Booking status Updated.',
                    'status' => 'success',
                ], $this->success);
            }
    }  

        else {

            return response()->json([
                'data' => ['data' => []],
                'message' => 'Booking status not Updated.',
                'status' => 'error',
            ], $this->error);
        }
           }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'error'], $this->error);
        }
    }


    
//Provider Side   

    //New Provider Booking Status
    public function providerBookingNew()
    {
        try {
        $user = Auth::user();
        //New Booking List
        $new_booking_list = Booking::with(['userbooking.mainservice'])->where([['provider_id',$user->id],['booking_status','pending']])->get();
        $new_booking = UserBookingListResource::collection($new_booking_list);
        
        if($new_booking_list->isNotEmpty())
        {
            return response()->json([
               
                'new_booking'=>$new_booking,
                'message' => 'New Provider Booking Status',
                'status' => 'success',
            ], $this->success);
        }
        else
        {
            return response()->json([ 'bookings'=>[],'error' => "No data Found", 'status' => 'error'],$this->success);
        }
    } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'error'], $this->error);
        }
    }
    
    //View Provider Booking Status
    public function providerBookingHistory()
    {
         try {
        $user = Auth::user();
        //Accept Booking List
        $accept_booking_list = Booking::with(['userbooking.mainservice'])
        ->where([['provider_id',$user->id],['booking_status','accepted']])
        ->orWhere([['provider_id',$user->id],['booking_status','request_completed']])
        ->orWhere([['provider_id',$user->id],['booking_status','on_the_way']])
        ->orWhere([['provider_id',$user->id],['booking_status','progress']])
        ->orWhere([['provider_id',$user->id],['booking_status','arrived']])
        ->get();
        
        $accept_booking = UserBookingListResource::collection($accept_booking_list);
        
        //Complete Booking List
        $complete_booking_list = Booking::with(['userbooking.mainservice'])->where([['provider_id',$user->id],['booking_status','completed']])->get();
        $complete_booking = UserBookingListResource::collection($complete_booking_list);
        
        
        //Cancelled Booking List
        $cancel_booking_list = Booking::with(['userbooking.mainservice'])->where([['provider_id',$user->id],['booking_status','cancelled']])->get();
        $cancel_booking = UserBookingListResource::collection($cancel_booking_list);
        
        
        if($accept_booking_list->isNotEmpty() || $complete_booking_list->isNotEmpty() || $cancel_booking_list->isNotEmpty())
        {
            return response()->json([
               
                'accept_booking'=>$accept_booking,
                'complete_booking'=>$complete_booking,
                'cancel_booking'=>$cancel_booking,
                'message' => 'All Provider Booking Status',
                'status' => 'success',
            ], $this->success);
        }
        else
        {
            return response()->json([ 'bookings'=>[],'error' => "No data Found", 'status' => 'error'],$this->success);
        }
         }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'error'], $this->error);
        }
    }

    // Booking Status Update
    public function updateBookingStatus(Request $request)
    {
        try {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'booking_id'=>'required',
            'booking_status'=>'required',
        ]);
        if($validator->fails()){
            return response()->json(['Field required' => $validator->errors()]);
        }
        
       $booking_total_cost = 0;
       $booking_data =[];
       $booking_detail_data =[];
       $status = $request->booking_status;
       $booking_id = $request->booking_id;
      
       $booking = Booking::find($booking_id);
       $customer = User::find($booking->user_id);
       
        if(!$booking)
        {
            return response()->json(['message' => 'Booking not Found', 'status' => 'error'], $this->error);
        }
        if($booking->provider_id != $user->id)
        {
              return response()->json(['message' => 'You have no rights to update the status', 'status' => 'error'], $this->error);
        }
       
       
        if ($status == 'accepted')
        {
            $booking_update = $booking->update(['booking_status' => $status, 'accepted' => 1]);
            if ($booking_update) {
                $booking_data = Booking::find($booking->id);
                // FireBase
                $message = "Your booking has been accepted";
                $data_array = ['title' => 'Booking Accepted', 'body' => $message, 'booking_id'=>$booking->id , 'type' => 'booking_accepted', 'description' => $message];
                $response = $customer->sendNotification($booking_data->user_id, $data_array, $message);
                NotificationUser::create(['user_id' => $user->id, 'send_to' => $booking_data->user_id, 'booking_uuid' => $booking_data->booking_id,'booking_id' => $booking_data->id, 'message' => $message, 'type' => "booking_accepted", 'redirect' => $booking_data->id]);
                // EndFireBase
                return response()->json([
                     'booking_data'=>$booking_data,
                    'message' => 'Booking status Updated.',
                    'status' => 'success',
                ], $this->success);
            }
        } 
        
        else if ($status == 'on_the_way') 
         {
            $booking_update = $booking->update(['booking_status' => $status, 'on_the_way' => 1]);
            if ($booking_update) {
                $booking_data = Booking::find($booking->id);
                // FireBase
                $message = "Your Booking Service Provider is On the way";
                $data_array = ['title' => 'Service Provider On The Way', 'body' => $message, 'booking_id'=>$booking->id , 'type' => 'booking_on_the_way', 'description' => $message];
                $response = $customer->sendNotification($booking_data->user_id, $data_array, $message);
                NotificationUser::create(['user_id' => $user->id, 'send_to' => $booking_data->user_id, 'booking_uuid' => $booking_data->booking_id,'booking_id' => $booking_data->id, 'message' => $message, 'type' => "provider_on_the_way", 'redirect' => $booking_data->id]);
                // EndFireBase
                return response()->json([
                     'booking_data'=>$booking_data,
                    'message' => 'Booking status Updated.',
                    'status' => 'success',
                ], $this->success);
            }
    } 
     
        else if ($status == 'request_completed') 
        {
            $booking_update = $booking->update(['booking_status' => $status, 'request_completed' => 1]);
            if ($booking_update) {
                $booking_data = Booking::find($booking->id);
                // FireBase
                $message = "Request for  Booking complete";
                $data_array = ['title' => 'Booking Completed', 'body' => $message,'booking_id'=>$booking->id , 'type' => 'booking_request_completed', 'description' => $message];
                $response = $customer->sendNotification($booking_data->user_id, $data_array, $message);
                NotificationUser::create(['user_id' => $user->id, 'send_to' => $booking_data->user_id, 'booking_uuid' => $booking_data->booking_id,'booking_id' => $booking_data->id, 'message' => $message, 'type' => "booking_request_completed", 'redirect' => $booking_data->id]);
                // EndFireBase
                return response()->json([
                    'booking_data'=>$booking_data,
                    'message' => 'Booking status Updated.',
                    'status' => 'success',
                ], $this->success);
            }
    }  
    
        else if ($status == 'cancelled') 
        {
            $booking_update = $booking->update(['booking_status' => $status, 'cancelled' => 1]);
            if ($booking_update) {
                $booking_data = Booking::find($booking->id);
                // FireBase
                $message = "Your Booking is Cancelled";
                $data_array = ['title' => 'Booking Cancelled', 'body' => $message,'booking_id'=>$booking->id , 'type' => 'booking_cancelled', 'description' => $message];
                $response = $customer->sendNotification($booking_data->user_id, $data_array, $message);
                NotificationUser::create(['user_id' => $user->id, 'send_to' => $booking_data->user_id, 'booking_uuid' => $booking_data->booking_id,'booking_id' => $booking_data->id, 'message' => $message, 'type' => "booking_cancelled", 'redirect' => $booking_data->id]);
                // EndFireBase
                return response()->json([
                    'booking_data'=>$booking_data,
                    'message' => 'Booking status Updated.',
                    'status' => 'success',
                ], $this->success);
            }
    }  
    
        else if ($status == 'arrived') 
        {
            $booking_update = $booking->update(['booking_status' => $status, 'arrived' => 1]);
            if ($booking_update) {
                $booking_data = Booking::find($booking->id);
                // FireBase
                $message = "Your Booking was Arrived";
                $data_array = ['title' => 'Booking Arrived', 'body' => $message,'booking_id'=>$booking->id , 'type' => 'booking_arrived', 'description' => $message];
                $response = $customer->sendNotification($booking_data->user_id, $data_array, $message);
                NotificationUser::create(['user_id' => $user->id, 'send_to' => $booking_data->user_id, 'booking_uuid' => $booking_data->booking_id,'booking_id' => $booking_data->id, 'message' => $message, 'type' => "booking_arrived", 'redirect' => $booking_data->id]);
                // EndFireBase
                return response()->json([
                    'booking_data'=>$booking_data,
                    'message' => 'Booking status Updated.',
                    'status' => 'success',
                ], $this->success);
            }
    }  
    
        else if ($status == 'progress') 
        {
            $booking_update = $booking->update(['booking_status' => $status, 'progress' => 1]);
            if ($booking_update) {
                $booking_data = Booking::find($booking->id);
                // FireBase
                $message = "Your Booking was Progress";
                $data_array = ['title' => 'Booking Arrived', 'body' => $message,'booking_id'=>$booking->id , 'type' => 'booking_progress', 'description' => $message];
                $response = $customer->sendNotification($booking_data->user_id, $data_array, $message);
                NotificationUser::create(['user_id' => $user->id, 'send_to' => $booking_data->user_id, 'booking_uuid' => $booking_data->booking_id,'booking_id' => $booking_data->id, 'message' => $message, 'type' => "booking_progress", 'redirect' => $booking_data->id]);
                // EndFireBase
                return response()->json([
                    'booking_data'=>$booking_data,
                    'message' => 'Booking status Updated.',
                    'status' => 'success',
                ], $this->success);
            }
    }  
    
        else {

            return response()->json([
                'data' => ['data' => []],
                'message' => 'Booking status not Updated.',
                'status' => 'error',
            ], $this->error);
        }
           }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'error'], $this->error);
        }
    }







}
