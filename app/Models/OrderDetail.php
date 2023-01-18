<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
    'order_id',
    'user_id',
    'provider_id',
    'main_service_id',
    'sub_service_id',
    'service_charges',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function provider()
    {
        return $this->belongsTo(User::class,'provider_id');
    }
     public function subservice()
    {
        return $this->belongsTo(SubService::class,'sub_service_id');
    }
  
}
