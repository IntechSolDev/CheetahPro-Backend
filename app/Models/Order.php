<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
       'order_id',
        'user_id',
        'provider_id',
        'main_service_id',
        'sub_service_id',
        'order_date',
        'hours',
        'amount',
        'address',
        'accepted',
        'on_the_way',
        'new',
        'completed',
        'rejected',
        'rejected_comment',
        'current_status',
        ];
  
      public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function provider()
    {
        return $this->belongsTo(User::class,'provider_id');
    }
      public function mainservice()
    {
        return $this->belongsTo(User::class,'main_service_id');
    }
     public function rating()
    {
        return $this->belongsTo(Rate::class,'order_id');
    }
        protected $casts = [
        'accepted'=>'boolean',
        'on_the_way'=>'boolean',
        'new'=>'boolean',
        'amount' => 'float',
        'completed'=>'boolean',
        'rejected'=>'boolean',

    ];
}
