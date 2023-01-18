<?php

namespace App\Http\Resources;

use App\Models\Rate;
use App\Models\UserService;
use App\Models\SubService;
use App\Http\Resources\RateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserServiceListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $rate = Rate::where('rate_to',$this->id)->orderby('created_at','desc')->get();
        $take_data = $rate->take(2);
        $user_rate =  RateResource::collection($take_data);
        $sub_service = SubService::join('user_services','sub_services.id','user_services.sub_service_id')->where('user_services.user_id',$this->id)->get();
        return[
            'user_id'=>$this->id,
            'sub_service_id'=>$this->sub_service_id,
            'name'=>$this->first_name .' '.$this->last_name ,
            'image'=>$this->image != null ? asset('/public/assets/images/user/' . $this->image) : null,
            'icon'=>$this->icon != null ? asset('/public/assets/images/user/' . $this->icon) : null,
            'about'=>$this->about != null ? $this->about : "",
            'sub_service'=>$sub_service,
            'rate'=>round($rate->avg('rate')),
            'reviews'=>$user_rate,
            'charges'=>$this->charges,
        ];
    }
}
