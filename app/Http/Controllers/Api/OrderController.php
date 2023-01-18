<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\UserService;
use App\Models\Rate;
use App\Models\User;
use App\Models\SubService;
use App\Models\NotificationUser;
use App\Http\Resources\RateResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderResourceProvider;
use App\Http\Resources\OrderDetailResource;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    //Response Status
    public $success = 200;
    public $error = 404;
    public $validate_error = 401;

// Order --------- Create an Order
    public function createOrder(Request $request)
    {
        
        try {

        $user = Auth::user();
        $order_data = [];
        $order_total_cost= 0;
        $validator = Validator::make($request->all(), [
            'sub_service'=>'required',
             'provider_id'=>'required',
        ]);
        
        
        if($validator->fails()){
            return response()->json(['Service required' => $validator->errors()]);
        }
        $sub_service = $request->sub_service;
        $order_data['user_id']=$user->id;
        $order_data['order_id'] = 'CP'.rand ( 10000 , 99999 ).'U'. $user->id;
        $order_data['provider_id']=$request->provider_id;
        $order_data['address']=$request->address;
        $order_data['order_date'] =  $request->order_date;
        $order_data['order_email'] = $user->email;
        $order=Order::create($order_data);
        if ($sub_service) {
            foreach ($sub_service as $sub_serv) {
                        $user_service = UserService::where([['user_id', $request->provider_id],['sub_service_id',$sub_serv]])->first();
                        $order_data['amount'] = $user_service->service_charges;
                if($user_service)
                {
                    $order_detail_data['order_id'] = $order->id;
                    $order_detail_data['user_id'] = $user->id;
                    $order_detail_data['provider_id'] =$request->provider_id;
                    $order_detail_data['main_service_id'] = $user_service->main_service_id;
                    $order_detail_data['sub_service_id'] = $sub_serv;
                    $order_detail_data['service_charges'] =  $user_service->service_charges;
                    $order_detail = OrderDetail::create($order_detail_data);
                    $order_total_cost = $order_total_cost + $user_service->service_charges;
                }
            }
        }
        $order_data['amount'] = $order_total_cost;
        $order=Order::updateOrCreate(['id'=>$order->id],$order_data);
        
        
        
        if($order){
            //  $orderdata = new Order();
            //  $maildata =  $orderdata->getmaildata($order->id);
            //   \Mail::to(env('MAIL_ORDER_ADDRESS'))->send(new \App\Mail\Invoice($maildata));
            
            
        // FireBase
        $message = "Congrats! New order Place";
        $data_array = ['title' => 'Order Placed', 'body' => $message, 'type' => 'order_placed','user'=>$user,'order_id'=>$order->id,'description' => $message];
        $user->sendNotification($request->provider_id, $data_array, $message);
        $notify = NotificationUser::create(['user_id' => $user->id, 'send_to' => $request->provider_id, 'message' => $message, 'type' => "order_placed", 'redirect' => $order->id]);
        // EndFireBase

            return response()->json(['message' => "Your Order is created Successfully",'order_id'=>$order->id, 'status' => 'success'], $this->success);
        }
        else{
            return response()->json(['message' => 'Order not Placed', 'status' => 'error'], $this->error);
        }
        
         

} catch (\Exception $e) {
 return response()->json(['message' => $e->getMessage(), 'status' => 'error'], $this->error);
   
}
    }


 // Customer
 //Past orders---------- View all customer Order
    public function viewOrderList(Request $request)
    {
        $user = Auth::user();
        $orders = Order::where('user_id',$user->id)->latest()->paginate(20);
        $order_data = OrderResource::collection($orders)->response()->getData(true);

        if ($orders->isNotEmpty()) {
            return response()->json([
                'order_list' => $order_data,
                'message' => 'Order Data.',
                'status' => 'success',
            ], $this->success);
        }

        return response()->json([
            'order_list' => [],
            'message' => 'Order Data not Found.',
            'status' => 'success',
        ], $this->success);
    }

//Cancel Order

    public function cancelOrder(Request $request)
    {
      
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'order_id'=>'required',
            'order_status'=>'required',
            'comment'=>'required',
        ]);
        if($validator->fails()){
            return response()->json(['Field required' => $validator->errors()]);
        }
        
       $order_total_cost = 0;
       $order_data =[];
       $order_detail_data =[];
       $status = $request->order_status;
       $order_id = $request->order_id;
       $comment  = $request->comment;
       $order = Order::find($order_id);
       $provider  = User::find($order->provider_id);
       
       if(!$order)
        {
            return response()->json(['message' => 'Order not Found', 'status' => 'error'], $this->error);
        }
        if($order->user_id != $user->id)
        {
              return response()->json(['message' => 'You have no rights to update the status', 'status' => 'error'], $this->error);
        }
       
   if ($status == 'canceled')
   {
            $order_update = $order->update(['current_status' => $status, 'rejected' => 1,'rejected_comment' => $comment]);
            if ($order_update) {
                $order_data = Order::find($order->id);
                // FireBase
                $message = "Your order has been cacnceled";
                $data_array = ['title' => 'Order Canceled', 'body' => $message, 'order_id'=>$order->id ,'type' => 'order_canceled', 'description' => $message];
                $response = $provider->sendNotification($provider->id, $data_array, $message);
                NotificationUser::create(['user_id' => $user->id, 'send_to' => $provider->id, 'order_uuid' => $order_data->order_id,'order_id' => $order_data->id, 'message' => $message, 'type' => "order_canceled", 'redirect' => $order_data->id]);
                // EndFireBase
                return response()->json([
                     'order_data'=>$order_data,
                    'message' => 'Order status Updated.',
                    'status' => 'success',
                ], $this->success);
            }
    } 
        
    else {

            return response()->json([
                'data' => ['data' => []],
                'message' => 'Order status not Updated.',
                'status' => 'error',
            ], $this->error);
        }
    }



 // Provider
 //Past orders---------- View all Provider order list with status
    public function viewOrderListProvider($filterby = null)
    {
        $user = Auth::user();
        if($filterby  != null)
        {
             $orders = Order::where([['provider_id',$user->id],['current_status',$filterby]])->latest()->paginate(20);
        }
      
        else
        {
        $orders = Order::where('provider_id',$user->id)->latest()->paginate(20);
        }
          $order_data = OrderResourceProvider::collection($orders)->response()->getData(true);
        if ($orders->isNotEmpty()) {
            return response()->json([
                'order_list' => $order_data,
                'message' => 'Order Data.',
                'status' => 'success',
            ], $this->success);
        }

        return response()->json([
            'order_list' => [],
            'message' => 'Order Data not Found.',
            'status' => 'success',
        ], $this->success);
    }
    

 //Past orders Detail---------- View Order Detail
    public function viewOrderDetail($order_id)
    {
        $user = Auth::user();
        $product_arr = [];
        $order = Order::find($order_id);
        $order_to = User::find($order->provider_id);
        $order_by = User::find($order->user_id);
        if(!$order)
        {
           return response()->json([
            'message' => 'No order Found by this user',
            'status' => 'error',
        ], $this->error);  
        }
        
      
         $order_list = OrderDetail::where('order_id',$order_id)->get();

        $order_detail_data = [];
          foreach($order_list as $order_item)
        {
        //   
            $service_data = SubService::join('user_services','sub_services.id','user_services.sub_service_id')->where('sub_services.id',$order_item->sub_service_id)->first();
           $service_detail[]= ['service_title'=>$service_data->title,'service_charges'=>$service_data->service_charges];
           
        }
        
        $rating= Rate::where('order_id',$order_id)->get();
        $user_rate =  RateResource::collection($rating);

   
        if ($service_detail) {
            return response()->json([
                 'order_to'=>$order_to,
                 'order_by'=>$order_by,
                 'orderdata'=>$order,
                'service_detail' => $service_detail,
                'rating'=>$user_rate,
                'message' => 'Order Detail.',
                'status' => 'success',
            ], $this->success);
        }

        return response()->json([
            'message' => 'Cant find any data',
            'status' => 'error',
        ], $this->error);
    }


 // View Order Detail By Order ID
    public function viewOrder($id)
    {
        $user = Auth::user();
        $order_data = Order::find($id)->with('product');
        if ($order_data) {
            return response()->json([
                'data' => $order_data,
                'message' => 'Order Data',
                'status' => 'success',
            ], $this->success);
        } else {
            return response()->json([
                'message' => 'Order not Found this specific User',
                'status' => 'error',
            ], $this->error);
        }
    }
    
    
 // Order Update
    public function updateOrderStatus(Request $request)
    {
      
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'order_id'=>'required',
            'order_status'=>'required',
        ]);
        if($validator->fails()){
            return response()->json(['Field required' => $validator->errors()]);
        }
        
       $order_total_cost = 0;
       $order_data =[];
       $order_detail_data =[];
       $status = $request->order_status;
       $order_id = $request->order_id;
      
       $order = Order::find($order_id);
       $customer = User::find($order->user_id);
       
       if(!$order)
        {
            return response()->json(['message' => 'Order not Found', 'status' => 'error'], $this->error);
        }
        if($order->provider_id != $user->id)
        {
              return response()->json(['message' => 'You have no rights to update the status', 'status' => 'error'], $this->error);
        }
       
   if ($status == 'accepted')
   {
            $order_update = $order->update(['current_status' => $status, 'accepted' => 1]);
            if ($order_update) {
                $order_data = Order::find($order->id);
                // FireBase
                $message = "Your order has been accepted";
                $data_array = ['title' => 'Order Accepted', 'body' => $message, 'order_id'=>$order->id , 'type' => 'order_accepted', 'description' => $message];
                $response = $customer->sendNotification($order_data->user_id, $data_array, $message);
                NotificationUser::create(['user_id' => $user->id, 'send_to' => $order_data->user_id, 'order_uuid' => $order_data->order_id,'order_id' => $order_data->id, 'message' => $message, 'type' => "order_accepted", 'redirect' => $order_data->id]);
                // EndFireBase
                return response()->json([
                     'order_data'=>$order_data,
                    'message' => 'Order status Updated.',
                    'status' => 'success',
                ], $this->success);
            }
    } 
        
    else if ($status == 'on_the_way') 
    {
            $order_update = $order->update(['current_status' => $status, 'on_the_way' => 1]);
            if ($order_update) {
                $order_data = Order::find($order->id);
                // FireBase
                $message = "Your order is On the way";
                $data_array = ['title' => 'Order On The Way', 'body' => $message, 'order_id'=>$order->id , 'type' => 'order_on_the_way', 'description' => $message];
                $response = $customer->sendNotification($order_data->user_id, $data_array, $message);
                NotificationUser::create(['user_id' => $user->id, 'send_to' => $order_data->user_id, 'order_uuid' => $order_data->order_id,'order_id' => $order_data->id, 'message' => $message, 'type' => "order_on_the_way", 'redirect' => $order_data->id]);
                // EndFireBase
                return response()->json([
                     'order_data'=>$order_data,
                    'message' => 'Order status Updated.',
                    'status' => 'success',
                ], $this->success);
            }
    } 
     
     else if ($status == 'completed') 
    {
            $order_update = $order->update(['current_status' => $status, 'completed' => 1]);
            if ($order_update) {
                $order_data = Order::find($order->id);
                // FireBase
                $message = "Your order is Completed";
                $data_array = ['title' => 'Order Completed', 'body' => $message,'order_id'=>$order->id , 'type' => 'order_completed', 'description' => $message];
                $response = $customer->sendNotification($order_data->user_id, $data_array, $message);
                NotificationUser::create(['user_id' => $user->id, 'send_to' => $order_data->user_id, 'order_uuid' => $order_data->order_id,'order_id' => $order_data->id, 'message' => $message, 'type' => "order_completed", 'redirect' => $order_data->id]);
                // EndFireBase
                return response()->json([
                    'order_data'=>$order_data,
                    'message' => 'Order status Updated.',
                    'status' => 'success',
                ], $this->success);
            }
    }    
    
    else {

            return response()->json([
                'data' => ['data' => []],
                'message' => 'Order status not Updated.',
                'status' => 'error',
            ], $this->error);
        }
    }
    
    
    public function changeOrderDate(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'order_id'=>'required',
            'order_date'=>'required'
        ]);
        if($validator->fails()){
            return response()->json(['Field required' => $validator->errors()]);
        }
       $order =  Order::where([['id',$request->order_id],['user_id',$user->id]])->first();
       if($order)
       {
          $order->update(['order_date'=>$request->order_date]); 
           return response()->json([
                    'message' => 'Order Date Updated.',
                    'status' => 'success',
                ], $this->success);
       }
       else
       {
               return response()->json([
                'message' => 'Order Date not Updated.',
                'status' => 'error',
            ], $this->error);
       }
    }
    
    

}
