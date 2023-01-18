<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;
   protected $fillable = [
        'user_id',
        'rate_to',
        'booking_id',
        'remark',
        'rate',
    ];
    public function user1()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function user2()
    {
        return $this->belongsTo(User::class,'rate_to');
    }
        public function service()
    {
        return $this->belongsTo(SubService::class,'sub_sevice_id');
    }
}
