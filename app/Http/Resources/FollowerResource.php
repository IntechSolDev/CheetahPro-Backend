<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FollowerResource extends JsonResource
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
            'user_id'=>$this->follower->id,
            'name'=>$this->follower->name,
            'email'=>$this->follower->email,
            'image'=> $this->follower->image,
            'fcm_token'=>$this->follower->fcm_token,
        ];
    }
}
