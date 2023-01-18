<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'image',
        'icon',
        'description',
        'order_by',
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
            return asset('/public/assets/images/services/' . $value);
        }

    }
   public function getIconAttribute($value)
    {
        if($value == null)
        {
            return null;
        }
        else
        {
            return asset('/public/assets/images/services/' . $value);
        }

    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
