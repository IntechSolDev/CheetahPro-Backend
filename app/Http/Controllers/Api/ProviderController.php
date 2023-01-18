<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProviderProfileResource;
use App\Http\Resources\ProviderRateResource;
use App\Http\Resources\ProviderSubServiceResource;
use App\Http\Resources\RateResource;
use App\Models\Rate;
use App\Models\Service;
use App\Models\SubService;
use App\Models\User;
use App\Models\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    public $success = 200;
    public $error = 404;


    public function viewProviderProfile($id)
    {
        $provider  = User::with(["rate","order","mainservice"])->where([['type','Service Provider'],['id',$id]])->first();
        $provider_profile = ProviderProfileResource::make($provider);
        if($provider)
        {
            return response()->json([
                'profile_data' => $provider_profile,
                'message' => 'Provider Profile Data.',
                'status' => 'success',
            ], $this->success);
        }
        else
        {
            return response()->json(['error' => "No data Found", 'status' => 'error'],$this->error);
        }
    }

    public function viewProviderReviews($id)
    {

        $provider  = User::with(["rate"])->where([['type','Service Provider'],['id',$id]])->first();
        $provider_reviews =  RateResource::collection($provider['rate']);

        if($provider)
        {
            return response()->json([
                'rate'=>$provider->avg_rating,
                'total_user_rated'=>count($provider['rate']),
                'profile_reviews' => $provider_reviews,
                'message' => 'Provider Profile Reviews.',
                'status' => 'success',
            ], $this->success);
        }
        else
        {
            return response()->json(['error' => "No data Found", 'status' => 'error'],$this->error);
        }
    }


}
