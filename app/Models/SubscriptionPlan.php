<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'stripe_plan',
        'stripe_product_id',
        'title',
        'sub_title',
        'plan_duration',
        'description',
        'duration',
        'currency',
        'plan_duration',
        'price',
        'sort_order',
        'status',
    ];
}
