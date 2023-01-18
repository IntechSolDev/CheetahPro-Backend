<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBookingService extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_id',
        'user_id',
        'provider_id',
        'main_service_id',
        'sub_service_id',
        'service_charges'
    ];
    public function mainservice()
    {
        return $this->hasOne(Service::class,'id','main_service_id');
    }
    public function subservice()
    {
        return $this->hasOne(SubService::class,'id','sub_service_id');
    }
    public function booking()
    {
        return $this->hasOne(Booking::class,'id','booking_id');
    }
}
