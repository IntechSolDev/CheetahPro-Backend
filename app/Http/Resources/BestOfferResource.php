<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BestOfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $charges=[];
        if($this->userservice)
        {
            $userservices = $this->userservice;
            foreach ($userservices as $userservice)
            {
                $charges[]= $userservice->service_charges;
            }
        }

        return [
            'id'=> $this->id,
            'title'=>$this->title,
            'description'=>$this->discription,
            'image'=> $this->image,
//            'charges'=>$charges,
//            'userservice'=>$this->userservice,
        ];
    }
}
