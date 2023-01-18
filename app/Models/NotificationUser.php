<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'send_to',
        'message',
        'title',
        'order_uuid',
        'type',
        'redirect',
        'status',
    ];
    protected $casts = [
        'status' => 'boolean',

    ];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
