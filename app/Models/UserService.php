<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserService extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'main_service_id',
        'sub_service_id',
        'service_name',
        'service_charges'
    ];

    public function subservice()
    {
        return $this->hasMany(SubService::class,'id','sub_service_id');
    }
    public function user()
    {
        return $this->hasMany(User::class,'id','user_id');
    }
    public function provider()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
     public function subservicename()
    {
        return $this->hasOne(SubService::class,'id','sub_service_id');
    }
}
