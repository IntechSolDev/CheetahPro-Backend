<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BestOfferResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProviderProfileResource;
use App\Http\Resources\ProviderListResource;
use App\Http\Resources\ProviderSubServiceResource;
use App\Http\Resources\SubServiceResource;
use App\Http\Resources\UserServiceListResource;
use App\Http\Resources\RateResource;
use App\Http\Resources\UserServiceResource;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Rate;
use App\Models\User;
use App\Models\Service;
use App\Models\UserGallery;
use App\Models\SubService;
use App\Models\UserService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\NotificationUser;

class ServiceController extends Controller
{
    //Response Status
    public $success = 200;
    public $error = 404;
    public $validate_error = 401;

    public function getMainService()
    {
        $user = Auth::user();
        $main_services = Service::get();

        if ($main_services->isNotEmpty()) {
            return response()->json([
                'main_service_list' => $main_services,
                'message' => 'Service Data.',
                'status' => 'success',
            ], $this->success);
        }
        else
        {
            return response()->json([
                'main_service_list' =>[],
                'message' => 'Service Data empty.',
                'status' => 'success',
            ], $this->success);
        }
    }


    public function getSubService($id)
    {
        $user = Auth::user();
        $sub_services = SubService::with(['userservice'])->where('main_service_id',$id)->get();
        $all_subservice = SubServiceResource::collection($sub_services);
        if ($sub_services->isNotEmpty()) {
            return response()->json([
                'sub_service_list' => $all_subservice,
                'message' => 'Sub Service Data.',
                'status' => 'success',
            ], $this->success);
        }
        else
        {
            return response()->json([
                'sub_service_list' =>[],
                'message' => 'Sub Service Data empty.',
                'status' => 'success',
            ], $this->success);
        }
    }

    public function getBestOffer()
    {
        $user = Auth::user();
        $sub_services = SubService::with('userservice')->get();
        $best_services =  BestOfferResource::collection($sub_services);
        if ($sub_services->isNotEmpty()) {
            return response()->json([
                'best_service_list' => $best_services,
                'message' => 'Sub Service Data.',
                'status' => 'success',
            ], $this->success);
        }
        else
        {
            return response()->json([
                'sub_service_list' =>[],
                'message' => 'Sub Service Data empty.',
                'status' => 'success',
            ], $this->success);
        }
    }

    public function getUserByService($id)
    {
        $user = Auth::user();
        $user_services =  UserService:: where([['user_services.main_service_id',$id],['users.stripe_status',1]])
            ->join('users','users.id','user_services.user_id')
            ->groupBy('user_services.user_id')
            ->select('users.*','user_services.sub_service_id','user_services.sub_service_id','user_services.service_charges as charges')
            ->get();

        $user_service_list = UserServiceListResource::collection($user_services);
        if(!$user_services)
        {
            return response()->json(['message' => 'Sub Service not found.','status' => 'error',], $this->error);
        }
        if ($user_services) {
            return response()->json([
                'user_service_list' => $user_service_list,
                'message' => 'User Service List data.',
                'status' => 'success',
            ], $this->success);
        }
        else
        {
            return response()->json([
                'sub_service_list' =>[],
                'message' => 'User Service List empty.',
                'status' => 'success',
            ], $this->error);
        }
    }

    public function getProviderBySubService($id,$option = null,$search = null)
    {
           try {
                  $provider_list = UserService::query();
                  $provider_list->where('sub_service_id',$id)->join('users','user_services.user_id','users.id')->select('users.id as user_id','users.*','user_services.*','users.main_service_id as main_service');
           if($option != null && $search != null)
           {
               if($option == 'name')
               {
                     $provider_list->where('users.first_name', 'like', '%'.$search.'%')->orWhere([['sub_service_id',$id],['users.last_name', 'like', '%'.$search.'%']]);
               }
               if($option == 'post_code')
               {
                 
                     $provider_list->where('users.post_code',$search);
               }
           }
             $data =   $provider_list->get();
         if ($data->isNotEmpty()) {
              $all_provider = ProviderSubServiceResource::collection($data);
            return response()->json([
                'all_provider' => $all_provider,
                'message' => 'User Service List data.',
                'status' => 'success',
            ], $this->success);
        }
        else
        {
            return response()->json([
                'all_provider' =>[],
                'message' => 'No Provider Found By this Service',
                'status' => 'success',
            ], $this->success);
        }
           } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'error'], $this->error);
        }
    }

    public function providerByPostal($postal = null)
    {
        $user = Auth::user();
        $user_services =  User:: with(["order","mainservice"])->where([['type','Service Provider'],['post_code',$postal]])->get();
        if(!$user_services)
        {
            return response()->json(['message' => 'User not found.','status' => 'error',], $this->error);
        }
        if ($user_services) {
             $all_provider = ProviderListResource::collection($user_services);
            return response()->json([
                'provider_list' => $all_provider,
                'message' => 'Provider List data.',
                'status' => 'success',
            ], $this->success);
        }
        else
        {
            return response()->json([
                'provider_list' =>[],
                'message' => 'Provider List empty.',
                'status' => 'success',
            ], $this->error);
        }
    }



    public function getAllPopularService()
    {
       try {
           
             $popular_services = SubService::orderBy('order_by')->take(5)->get();
     
         if ($popular_services->isNotEmpty()) {
              $all_popular = SubServiceResource::collection($popular_services);
            return response()->json([
                'all_popular' => $all_popular,
                'message' => 'All Popular Service List data.',
                'status' => 'success',
            ], $this->success);
        }
        else
        {
            return response()->json([
                '$all_popular' =>[],
                'message' => 'No Popular Service Found',
                'status' => 'error',
            ], $this->error);
        }
           } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'error'], $this->error);
        }  
    }

    public function addServiceReview(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'rate' => 'required|digits_between:0,5',
            'rate_to' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'status' => 'error'], 401);
        }
        if($user->id == $request->rate_to)
        {
            return response()->json(['message' => "You cant rate own service", 'status' => 'error'], 401);
        }
                $rate['user_id'] = $user->id;
                $rate['rate_to'] = $request->rate_to;
                $rate['booking_id'] = $request->booking_id;
                $rate['remark'] = $request->remark;
                $rate['rate'] = $request->rate;
                $rated = Rate::create($rate);
        if($rated)
        {
            $avg_rate = Rate::where('rate_to', $request->rate_to)->get();
            $final_avg = Ceil($avg_rate->avg('rate'));
            User::find($request->rate_to)->update(['avg_rating'=>$final_avg]);

            // FireBase
        $message = "Feedback given to your order";
        $data_array = ['title' => 'Order Feedback', 'body' => $message, 'type' => 'rating','user'=>$user,'order_id'=>$request->order_id,'description' => $message];
        $user->sendNotification($request->rate_to, $data_array, $message);
        $notify = NotificationUser::create(['user_id' => $user->id, 'send_to' => $request->rate_to, 'message' => $message, 'type' => "rating", 'redirect' => $request->order_id]);
        // EndFireBase
            return response()->json(['message' => "User Rate added Successfully", 'status' => 'success'],$this->success);

        }
        else
            return response()->json(['error' => "Rate was not added", 'status' => 'error'],$this->error);
    }



    public function allReviews($id)
    {


        $rate = Rate::where('rate_to',$id)->orderby('created_at','desc')->get();
        $user_rate =  RateResource::collection($rate);
        if(!$rate->isEmpty())
            return response()->json(['all_reviews'=>$user_rate,'message' => "All Reviews Successfully", 'status' => 'success'],$this->success);
        else
            return response()->json(['all_reviews'=>[],'error' => "Rate was not added", 'status' => 'error'],$this->error);
    }

   public function uploadImage(Request $request)
    {
        $user = Auth::user();
        if ($request->gallery_image_id != null) {
            $image_data = UserGallery::find($request->gallery_image_id);
            $url_path = parse_url($image_data->gallery_image, PHP_URL_PATH);
            $basename = pathinfo($url_path, PATHINFO_BASENAME);
            $file_old =  public_path("assets/images/user-gallery/$basename");
            unlink($file_old);
            if($request->is_delete)
            {
                $image_data->delete();
                $user = User::find($user->id);
                $user_gallery = UserGallery::where('user_id',$user->id)->get();
                $user_service_data = SubService::where('main_service_id',$user->main_service_id)->get();
                $user_service = UserServiceResource::collection($user_service_data);
                $user['sub_services'] = $user_service;
                $user['gallery_data'] = $user_gallery;
                return response()->json(['message' => "User Gallery Image has been deleted",'userdata'=>$user, 'status' => 'success'],$this->success);
            }
        }
        if ($request->hasFile('gallery_image')) {
            $extension = $request->gallery_image->extension();
            $filename = time() . "_." . $extension;
            $request->gallery_image->move(public_path('/assets/images/user-gallery'), $filename);
            $galleryimages['gallery_image'] = $filename;
            $galleryimages['user_id'] = $user->id;
            UserGallery::updateOrCreate(
                ['id' => $request->gallery_image_id],
                $galleryimages
            );
            $user = User::find($user->id);
            $user_gallery = UserGallery::where('user_id',$user->id)->get();
            $user_service_data = SubService::where('main_service_id',$user->main_service_id)->get();
            $user_service = UserServiceResource::collection($user_service_data);
            $user['sub_services'] = $user_service;
            $user['gallery_data'] = $user_gallery;
            return response()->json(['message' => "User Gallery Image has been added", 'userdata'=>$user,'status' => 'success'],$this->success);
        }
        else
            return response()->json(['error' => "Gallery image not uploaded", 'status' => 'error'],$this->error);

    }


}
