<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_uuid',
        'user_id',
        'provider_id',
        'note',
        'address',
        'booking_time',
        'booking_date',
        'is_today',
        'is_schedule',
        'accepted',
        'on_the_way',
        'completed',
        'rejected',
        'cancelled',
        'request_completed',
        'arrived',
        'progress',
        'rejected_comment',
        'total_amount',
        'booking_status',
    ];
    protected $casts = [
        'is_today' => 'boolean',
        'is_schedule'=>'boolean',
        'accepted'=>'boolean',
        'on_the_way'=>'boolean',
        'completed'=>'boolean',
        'rejected'=>'boolean',
        'cancelled'=>'boolean',
        'request_completed'=>'boolean',
        'arrived'=>'boolean',
        'progress'=>'boolean'
    ];
    public function userbooking()
    {
        return $this->hasMany(UserBookingService::class,'user_id','user_id');
    }
    public function provider()
    {
        return $this->hasOne(User::class,'id','provider_id');
    }
      public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
