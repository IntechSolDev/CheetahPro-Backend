<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGallery extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'gallery_image'

    ];

    public function getGalleryImageAttribute($value)
    {
        if($value == null)
        {
            return null;
        }
        else
        {
            return asset('/public/assets/images/user-gallery/' . $value);
        }
    }

}
