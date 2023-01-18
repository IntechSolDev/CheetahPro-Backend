<?php

namespace App\Http\Resources;

use App\Http\Resources\GetServiceChargeResource;
use App\Models\Rate;
use App\Models\UserBookingService;
use App\Models\UserService;
use App\Models\SubService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserBookingDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user_data = ['id'=>$this->user['id'],'name'=>$this->user['first_name'] .' '.$this->user['last_name'],'contactno'=>$this->user['contactno']];
        $provider_data = ['id'=>$this->provider['id'],'name'=>$this->provider['first_name'] .' '.$this->provider['last_name'],'contactno'=>$this->provider['contactno']];
        $user_buy_services = [];
        $get_all_subservice = UserBookingService::with(['mainservice','subservice'])->where('booking_id',$this->id)->get();
        $service_charges = GetServiceChargeResource::collection($get_all_subservice);
        return[
            'booking_id'=>$this->id,
            'booking_status'=>$this->booking_status,
            'booking_time'=>$this->booking_time,
            'booking_date'=>$this->booking_date,
            'booking_time_date'=> $this->booking_date . ' ' .$this->booking_time,
            'booking_address'=>$this->booking_address  ? $this->booking_address :   $this->user['street'] .' '. $this->user['city'] .' '. $this->user['state'],
            'booking_note'=>$this->note,
            'main_service_name'=>isset($get_all_subservice[0]->mainservice['title']) ? $get_all_subservice[0]->mainservice['title'] : null,
            'user_data'=>$user_data,
            'provider_data'=> $provider_data,
            'subservices_charges'=>$service_charges ? $service_charges : null,
        ];
    }
}
