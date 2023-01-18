<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SubService extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'main_service_id',
        'image',
        'description',
        'order_by',
        'bg_color',
        'status',
    ];

    public function getImageAttribute($value)
    {
        if($value == null)
        {
            return null;
        }
        else
        {
            return asset('/public/assets/images/subServices/' . $value);
        }

    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function userservice()
    {
        return $this->hasMany(UserService::class,'sub_service_id','id');
    }

    public function provider()
    {
        return $this->hasOne(UserService::class,'sub_service_id','id');
    }
}
