<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserServiceResource;
use App\Models\EmailVerfication;
use App\Models\RequestNotification;
use App\Models\PasswordReset;
use App\Models\SubService;
use App\Models\Service;
use App\Models\User;
use App\Models\UserGallery;
use App\Models\Order;
use App\Models\Booking;
use App\Models\UserService;
use App\Notifications\EmailVerificationRequest;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\File\getClientOriginalName;
use Validator;
use Illuminate\Support\Facades\Schema;

class UserController extends Controller
{
    public $successStatus = 200;
    public $errorStatus = 404;
    public function register(Request $request)
    {
        $input = $request->all();
        $gallery_image = $request->gallery;
        $validator = Validator::make($input, [
            'first_name'=>'required',
            'type'=>'required',
            'email' => 'unique:users|required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        //If any Validation fail
        if ($validator->fails()) {
             return response()->json(['status' => 'error','message'=>$validator->errors(),'validaterror'=>"1"], 401);
        }

        //Image

            if ($request->hasFile('image')) {
                //upload new file
                $extension = $request->image->extension();
                $filename = time().rand(100,999) . "_." . $extension;
                $request->image->move(public_path('/assets/images/user'), $filename);
                $input['image'] = $filename;
            }




        //Convert Password into hash
        $input['password'] = bcrypt($input['password']);
        $input['address'] = $request->street.' ' .$request->city .' '. $request->state;
        //Create User
        $user = User::create($input);

        //Update Token created user

        $api_token = $user->createToken($user->email)->accessToken;

//        User::where('id', $user->id)->update(['api_token' => $api_token]);

         $emailverfication = EmailVerfication::updateOrCreate(['email' => $user->email],
             [
                 'email' => $user->email,
                 'pin' => mt_rand(1000, 9999),
             ]
         );
        $user = User::find($user->id);
         $sub_services = SubService::get();
         $ser = Service::find($user->main_service_id);
         $user['main_service'] = isset($ser) ? $ser->title : null;
        $user['sub_services'] = $sub_services;
        if ($user) {
//             $user->notify(
//                 new EmailVerificationRequest($emailverfication->pin)
//             );
            
            return response()->json([
                "userdata" => $user,
                "token"=>$api_token,
                "message" => "User Registered Successfully",
                "status" => 'success','validaterror'=>"0"
            ],$this->successStatus);
        }

    }

    public function login(Request $request)
    {

        // Verification by Email

        // $is_verify =  User::where([['email', request('email')]])->first();

        // if(!$is_verify)
        // {
        //     return response()->json(['message' => 'Invalid Email',  "is_verified"=>true,'status' => 'error'], 401);
        // }

        // if($is_verify->email_verified_at == null)
        // {
        //     return response()->json([
        //             "message" => "User not verified",
        //             "is_verified"=>false,
        //             "status" => 'error'
        //         ]
        //         , $this->errorStatus);
        // }

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $api_token = $user->createToken($user->email)->accessToken;

            if ($request->fcm_token) {
                $user->fcm_token = $request->fcm_token;
                $user->image_url = $request->image_url;
                $user->update();
            }
            $user->save();

             $user_gallery = UserGallery::where('user_id',$user->id)->get();
             $user_service_data = SubService::where('main_service_id',$user->main_service_id)->get();

            $user_service = UserServiceResource::collection($user_service_data);
            $user['sub_services'] = $user_service;
            $user['gallery_data'] = $user_gallery;
            
            $ser = Service::find($user->main_service_id);
            $user['main_service'] = isset($ser) ? $ser->title : null;
            return response()->json([
                    'userdata' => $user,
                    'token'=>$api_token,
                    "message" => "User Logged In Successfully",
                    "status" => 'success'
                ]
                , $this->successStatus);
        } else {
            return response()->json(['message' => 'Invalid Username or Password', 'status' => 'error'], 401);
        }
    }

    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['message' => "User Not found", 'status' => 'error'], $this->errorStatus);
        }
        return response()->json([
            "userdata" => $user,
            "message" => "Get User Data Successfully",
            "status" => 'success'
        ], $this->successStatus);
    }

    // forgot password methods
    public function forgot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);
        if ($validator->fails()) {
            return response()->json(['message'=> $validator->errors(), 'status' => 'error'], 401);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user)
            return response()->json([
                'message' => "We can't find a user with that e-mail address.",
                'status' => 'error'
            ], $this->errorStatus);

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => mt_rand(1000, 9999),
            ]
        );
        if ($user && $passwordReset)
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );
        return response()->json([
            'message' => "We have e-mailed your password reset link!",
            'status' => 'success'
        ],$this->successStatus);
    }


//Update fcm token

public function updateFcmToken(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['message'=> "User Not found", 'status' => 'error'], $this->errorStatus);
        }
        $is_update = User::where('id',$user->id)->update(['fcm_token'=>$request->fcm_token]);
        if($is_update)
        {
            return response()->json([
            "message" => "Fcm Update Success",
            "status" => "success"],$this->successStatus);
        }
        else
        {
          return response()->json([
                'error' => "User Not Found", 'status' => 'error'
            ], $this->errorStatus);
        }
    }


    public function confirmCode(Request $request)
    {
        $token = $request->token;

        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        if (!$passwordReset)
            return response()->json([
                'error' => "This password reset token is invalid.", 'status' => 'error'
            ], $this->errorStatus);
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'error' => "This password reset token is invalid.", 'status' => 'error'
            ], $this->errorStatus);
        }
        return response()->json([$passwordReset,
            "message" => "Success",
            "status" => "success"]);
    }


    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find(Request $request)
    {
        $token = $request->token;

        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        if (!$passwordReset)
            return response()->json([
                'error' => "This password reset token is invalid.", 'status' => 'error'
            ], $this->errorStatus);
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'error' => "This password reset token is invalid.", 'status' => 'error'
            ], $this->errorStatus);
        }
        return response()->json([$passwordReset,
            "message" => "Success",
            "status" => "success"]);
    }

    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'status' => 'error'], 401);
        }

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.', 'status' => 'error'
            ], $this->errorStatus);
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            return response()->json([
                'message' => "We can't find a user with that e-mail address.", 'status' => 'error'
            ], $this->errorStatus);
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));
        return response()->json(['userdata' => $user, 'status' => 'success'], $this->successStatus);
    }

    public function edit(Request $request)
    {
        $user = Auth::user();
        $gallery_image = $request->gallery;

        if (!$user) {
            return response()->json(['message' => "User Not found", 'status' => 'error'], $this->errorStatus);
        }

        if ($request) {
            $requests = $request->except('password_confirmation');
            $input = $requests;

            if ($request->hasFile('image')) {
                //code for remove old file
                if ($user->image != null) {
                    $url_path = parse_url($user->image, PHP_URL_PATH);
                    $basename = pathinfo($url_path, PATHINFO_BASENAME);
                    $file_old =  public_path("assets/images/user/$basename");
                    unlink($file_old);
                }
                //upload new file
                $extension = $request->image->extension();
                $filename = time() . "_." . $extension;
                $request->image->move(public_path('/assets/images/user'), $filename);
                $input['image'] = $filename;
            }

            if ($gallery_image) {
                foreach ($gallery_image as $gallery) {
                    $extension = $gallery->extension();
                    $filename = time() . rand(100, 999) . "_." . $extension;
                    $gallery->move(public_path('/assets/images/user-gallery'), $filename);
                    $galleryimages['gallery_image'] = $filename;
                    $galleryimages['user_id'] = $user->id;
                    UserGallery::create($galleryimages);
                }
            }

            if($request->password != null && $request->password_confirmation != null)
            {
                if($request->password_confirmation == $request->password)
                    $input['password'] = Hash::make($request->password);
                else
                 return response()->json(['message' => 'Your Password and Confirm are mismatch', 'status' => 'error'], $this->errorStatus);
            }



            if($request->service)
            {
                $response = $this->editUserService($request);

            }

            //prevent user not update email
                $input['email'] = $user->email;
                unset($input['service']);
                unset($input['gallery']);
                User::where('id', $user->id)->update($input);
                $userdata= User::find($user->id);


            $user_gallery = UserGallery::where('user_id',$user->id)->get();
            $user_service_data = SubService::where('main_service_id',$user->main_service_id)->get();
            $user_service = UserServiceResource::collection($user_service_data);


            $userdata['sub_services'] = $user_service;
            $userdata['gallery_data'] = $user_gallery;


            return response()->json(['userdata' => $userdata,'message' => "User Data Updated Successfully", 'status' => 'success'],$this->successStatus);
        } else {
            return response()->json(['error' => "User Data was not Updated ", 'status' => 'error'],$this->errorStatus);
        }

    }




    public function editUserService($request)
    {
        $services = $request->service;
        $service_list = [];
        $user = auth('api')->user();
        if ($services) {
            foreach ($services as $service) {
                $tempData = str_replace("\\", "",$service);
                $tempData = trim($tempData,'"');
                $service_data = json_decode($tempData);
                $sub = SubService::find($service_data->sub_service_id);
                if(!$sub)
                {
                    return response()->json(['error' => "Sub Service not found", 'status' => 'error'],$this->errorStatus);
                }
                $user_service['main_service_id'] = $user->main_service_id;
                $user_service['sub_service_id'] = $service_data->sub_service_id;
                $user_service['user_id'] = $user->id;
                $user_service['service_charges'] = $service_data->service_charges;
                $service_list[]= $service_data->sub_service_id;
                $update_service =  UserService::updateOrCreate(
                    ['user_id' => $user->id,'sub_service_id'=> $service_data->sub_service_id],
                    ['service_charges'=>$service_data->service_charges,'main_service_id'=>$user->main_service_id]
                );
            }
            UserService::where('user_id',$user->id)->whereNotIn('sub_service_id',$service_list)->delete();
        }
        if($update_service)
        {
            $user = User::find($user->id);
            $user_gallery = UserGallery::where('user_id',$user->id)->get();
            $user_service_data = SubService::where('main_service_id',$user->main_service_id)->get();
            $user_service = UserServiceResource::collection($user_service_data);
            $user['sub_services'] = $user_service;
            $user['gallery_data'] = $user_gallery;
            return response()->json(['userdata'=>$user,'message' => "Service Updated Successfully", 'status' => 'success'],$this->successStatus);
        }
          else
            return response()->json(['error' => "Service was not Updated ", 'status' => 'error'],$this->errorStatus);
    }

   public function editProviderService(Request $request)
    {
        $user = auth('api')->user();
        $main_service_id = $request->main_service_id;
        $is_not_completed = Booking::where([['provider_id',$user->id],['booking_status','!=','completed'],['booking_status','!=','cancelled'],['booking_status','!=','progress']])->first();
        if($is_not_completed)
        {
             return response()->json(['error' => "Service can not be Updated on that moment", 'status' => 'error'],$this->errorStatus);
        }
        if($request->main_service_id != $user->main_service_id)
        {
         $user->update(['main_service_id'=>$request->main_service_id]);  
         UserService::where('user_id',$user->id)->where('main_service_id',$request->main_service_id)->delete();
        }
        
        $services = $request->service;
        $service_list = [];
        
        if ($services) {
            foreach ($services as $service) {
                $tempData = str_replace("\\", "",$service);
                $tempData = trim($tempData,'"');
                $service_data = json_decode($tempData);
                $sub = SubService::find($service_data->sub_service_id);
                if(!$sub)
                {
                    return response()->json(['error' => "Sub Service not found", 'status' => 'error'],$this->errorStatus);
                }
                $user_service['main_service_id'] = $request->main_service_id ? $request->main_service_id : $user->main_service_id;
                $user_service['sub_service_id'] = $service_data->sub_service_id;
                $user_service['user_id'] = $user->id;
                $user_service['service_charges'] = $service_data->service_charges;
                $service_list[]= $service_data->sub_service_id;
                $update_service =  UserService::updateOrCreate(
                    ['user_id' => $user->id,'sub_service_id'=> $service_data->sub_service_id],
                    ['service_charges'=>$service_data->service_charges,'main_service_id'=>$user->main_service_id]
                );
               
            }
            UserService::where('user_id',$user->id)->whereNotIn('sub_service_id',$service_list)->delete();
          
            
        }
        if($update_service)
        {
            $user = User::find($user->id);
            $user_gallery = UserGallery::where('user_id',$user->id)->get();
            $user_service_data = SubService::where('main_service_id',$user->main_service_id)->get();
            $user_service = UserServiceResource::collection($user_service_data);
            $ser = Service::find($user->main_service_id);
            $user['sub_services'] = $user_service;
            $user['gallery_data'] = $user_gallery;
            $user['main_service'] = $ser->title;
            return response()->json(['userdata'=>$user,'message' => "Service Updated Successfully", 'status' => 'success'],$this->successStatus);
        }
          else
            return response()->json(['error' => "Service was not Updated ", 'status' => 'error'],$this->errorStatus);
    }
    
    
    
    
    public function changePassword(Request $request)
    {
        $user_id = auth('api')->user()->id;
        $user = User::find($user_id);
        if (!$user) {
            return response()->json(['message' => 'User not found by this Id', 'status' => 'error'], $this->errorStatus);
        }

        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:6',
            'password' => 'required|string|confirmed|min:6|max:11',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'status' => 'error'], $this->errorStatus);
        }

        if (Hash::check($request->old_password, $user->password)) {
            $user->fill(['password' => Hash::make($request->password)])->save();
            return response()->json(['userdata' => $user, 'status' => 'success'], $this->successStatus);
        } else {
            $error['old_password'] = ['Your old password was incorrect'];

            return response()->json(['message' => $error, 'status' => 'error'], $this->errorStatus);
        }

    }


    public function verifyEmail(Request $request)
    {

        $token = $request->pin;

        $emailverfication = EmailVerfication::where('pin', $token)->first();
        if (!$emailverfication)
            return response()->json([
                'error' => "This Verfication Code is invalid."
            ], $this->errorStatus);
        User::where('email', $emailverfication->email)->update(['email_verified_at' => Carbon::now()]);
        $user = User::where('email', $emailverfication->email)->first();

        $user->api_token = $user->createToken($emailverfication->email)->accessToken;
        if (!$emailverfication)
            return response()->json([
                'message' => "This Verfication Code is invalid.", 'status' => 'error'
            ], $this->errorStatus);
        if (Carbon::parse($emailverfication->updated_at)->addMinutes(720)->isPast()) {
            $emailverfication->delete();
            return response()->json([
                'message' => "Pin Expired.", 'status' => 'error'
            ], $this->errorStatus);
        }
        return response()->json([$user,
            "success" => "Email Verfied Successfully"],$this->successStatus);
    }

    public function confirmPassword($id, Request $request)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found by this Id', 'status' => 'error'], $this->errorStatus);
        }
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'This password confirmation not match', 'status' => 'error'], $this->errorStatus);
        }

        if (Hash::check($request->old_password, $user->password)) {
            $user->fill(['password' => Hash::make($request->password)])->save();
            return response()->json(['userdata' => $user, 'status' => 'success'], $this->successStatus);
        } else {

            return response()->json(['message' => 'Your old password was incorrect', 'status' => 'error'], $this->errorStatus);
        }

    }

  public function delete() {
    $user = User::where('id','>',0)->delete();
    if($user)
        return response()->json(['message' => "User  Deleted", 'status' => 'success']);
    else
       return response()->json(['message' => "User Not Deleted", 'status' => 'error'], $this->errorStatus);
     }

}
