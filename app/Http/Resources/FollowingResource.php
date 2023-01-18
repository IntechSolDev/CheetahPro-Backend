<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FollowingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'user_id'=>$this->following->id,
            'name'=>$this->following->name,
            'email'=>$this->following->email,
            'image'=> $this->following->image,
            'fcm_token'=>$this->following->fcm_token,
        ];

    }
}
