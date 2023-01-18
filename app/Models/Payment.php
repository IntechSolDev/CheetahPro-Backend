<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable =[
        's_payment_id',
        'user_id',
        'valid_date',
        'amount',
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
