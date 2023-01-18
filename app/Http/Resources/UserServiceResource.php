<?php

namespace App\Http\Resources;

use App\Models\UserService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       
        $user = Auth::user();
        $user_service = UserService::where([['user_id',$user->id],['sub_service_id',$this->id]])->first();
        return[
            'id'=>$this->id,
            'title'=>$this->title,
            'image'=>$this->image,
            'icon'=>$this->icon,
            'description'=>$this->description,
            'order_by'=>$this->order_by,
            'status'=>$this->status,
            'user_service_charge'=>$user_service ? $user_service->service_charges : "" ,
            'is_selected'=>$user_service ? true : false,
            'date'=>$this->created_at->format('M d, Y')
        ];
    }
}
