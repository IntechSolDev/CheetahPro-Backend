<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name',
        'user_id',
        'category_id',
        'image',
        'gallery',
        'unitPrice',
        'minQty',
        'multQty',
        'longDesc',
        'category',
        'status',
    ];
       protected $casts = [
        'status' => 'boolean',
        'gallery'=>'array',
    ];

    public function getImageAttribute($value)
    {
        if($value == null)
        {
            return null;
        }
        else
        {
            return asset('/public/assets/images/product/' . $value);
        }

    }

    public function getGalleryAttribute($value)
    {
        $arr=[];
        $image1= json_decode($value);
        if(!empty($image1))
        {

            foreach($image1 as $val){
                $arr[]= asset('/public/assets/images/product/' . $val);

            }
            return $arr;

        }
        return true;

    }
}
