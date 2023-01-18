<?php

namespace App\Http\Resources;

use App\Models\Rate;
use App\Models\UserService;
use App\Models\Service;
use App\Models\UserBookingService;
use App\Models\SubService;
use App\Http\Resources\RateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserBookingListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         $main_ser =  UserBookingService::where('booking_id',$this->id)->groupBy('booking_id')->pluck('main_service_id');
         $main_service = Service::find($main_ser);
        return[
            'booking_id'=>$this->id,
            'booking_status'=>$this->booking_status,
            'booking_time'=>$this->booking_time,
            'booking_date'=>$this->booking_date,
            'booking_charge'=>$this->total_amount,
            'booking_address'=>$this->address,
            'booking_time_date'=> $this->booking_date . ' ' .$this->booking_time,
            'main_service_name'=>$main_service[0]->title,
            'main_service_image'=>$main_service[0]->image,
            'main_service_icon'=>$main_service[0]->icon,

        ];
    }
}
