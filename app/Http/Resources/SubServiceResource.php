<?php

namespace App\Http\Resources;

use App\Models\UserService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class SubServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         $userdata = Auth::user();
         $is_service = null;
         $service_charges = null;
         $user_serv = UserService::where([['sub_service_id',$this->id],['user_id',$userdata->id]])->first();
         
         
         
        return[
            'id' =>$this->id,
            'main_service_id' =>$this->main_service_id,
            'title'  =>$this->title,
            'description' =>$this->description,
            'image' =>$this->image,
            'rate'=>$this->avg_rating,
            'order_by' =>$this->order_by,
            'bg_color' =>$this->bg_color,
            'status'=>$this->status,
            'total_provider'=>count($this->userservice),
            'is_service'=>isset($user_serv) ? true : null,
            'service_charges'=>isset($user_serv) ? $user_serv->service_charges : null,
            'date'=>$this->created_at->format('M d, Y')
        ];
    }
}
