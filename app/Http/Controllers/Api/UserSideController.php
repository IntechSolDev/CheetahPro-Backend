<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProviderRateResource;
use App\Http\Resources\RateResource;
use App\Models\Rate;
use App\Models\Service;
use App\Models\SubService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSideController extends Controller
{
    public $success = 200;
    public $error = 404;
    public function userHome($post_code = null)
    {
        $user = Auth::user();
        if($post_code)
        {
        $get_all_userId = User::where([['post_code',$post_code],['type','Service Provider']])->distinct()->pluck('main_service_id');
        }
        
        $recommended_services =[];

        //Main Service
        $main_services = Service::get();

        //Popular Service
        if($post_code)
        {
                  $popular_services = SubService::orderBy('order_by')->whereIn('main_service_id',$get_all_userId)->take(5)->get();
        }
        else
        {
                  $popular_services = SubService::orderBy('order_by')->take(5)->get();
        }
        //Get Recommended
        if($post_code)
        {
                  $recommended_provider  = User::with(["rate","order","mainservice"])->where([['type','Service Provider'],['post_code',$post_code]])->take(5)->get();
        }
         else
        {
                  $recommended_provider  = User::with(["rate","order","mainservice"])->where('type','Service Provider')->take(5)->get();
        }
        $all_recommended = ProviderRateResource::collection($recommended_provider);
        $statisticCollection = collect($all_recommended);
        $sorted = $statisticCollection->sortByDesc('rate');
        foreach($sorted as $sorting){ $recommended_services[]=$sorting; }

        return response()->json([
                'main_services' => $main_services,
                'popular_services' => $popular_services,
                'recommended_services' => $recommended_services,
                'message' => 'Service Data.',
                'status' => 'success',
        ], $this->success);

    }
}
