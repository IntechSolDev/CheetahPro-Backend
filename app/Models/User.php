<?php

namespace App\Models;

use App\Notifications\VerifyApiEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Laravel\Cashier\Billable;
use Cache;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable , Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $guarded = ['service'];

    protected $fillable = [
        'first_name',
        'last_name',
        'main_service_id',
        'about',
        'email',
        'password',
        'image',
        'contactno',
        'address',
        'street',
        'city',
        'state',
        'avg_rating',
        'post_code',
        'type',
        'current_type',
        'main_service',
        'gallery',
        'status',
        'fcm_token',
        'latitude',
        'longitude',
        'is_subscribe',
        'stripe_customer_id',
        'stripe_connect_id',
        'stripe_status'.
        'subscription_trial',
        'subscription_valid',
        'subscription_status'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'trial_ends_at'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'gallery'=>'array',
        'is_subscribe'=>'boolean',
        'subscription_status' => 'boolean',
        'subscription_trial'=> 'boolean'
    ];

    public function getImageAttribute($value)
    {
        if($value == null)
        {
           return null;
        }
        else
        {
            return asset('/public/assets/images/user/' . $value);
        }

    }

    public function getGalleryAttribute($value)
    {
        $arr=[];
        $image1= json_decode($value);
        if(!empty($image1))
        {

            foreach($image1 as $val){
                $arr[]= asset('/public/assets/images/user-service/' . $val);

            }
            return $arr;

        }
        return true;

    }


        public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

  public function sendNotification($user_id,$data_array,$message)
    {
        $userdata = User::find($user_id);
        $firebaseToken = [$userdata->fcm_token];
        $SERVER_API_KEY = env('FIRE_BASE_SERVER_API_KEY');
        if(isset($data_array['booking_id'])) {
            $data = [
                "registration_ids" => $firebaseToken,
                "notification" => [
                    "title" => $data_array['title'],
                    "body" => $data_array['body'],
                ],

                "data" => ['description' => $data_array['description'], 'type' => $data_array['type'], 'booking_id' => $data_array['booking_id']]

            ];
        }
        else
        {
            $data = [
                "registration_ids" => $firebaseToken,
                "notification" => [
                    "title" => $data_array['title'],
                    "body" => $data_array['body'],
                ],

                "data" => ['description' => $data_array['description'], 'type' => $data_array['type'], 'order_id' => $data_array['order_id']]

            ];
        }

        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);

        return response()->json(['success' =>$response]);
    }

    public function mainservice()
    {
        return $this->hasOne(Service::class,'id','main_service_id');
    }
    public function userservice()
    {
        return $this->hasMany(UserService::class,'id','user_id');
    }
    public function rate()
    {
        return $this->hasMany(Rate::class,'rate_to','id');
    }
    public function order()
    {
        return $this->hasMany(Order::class,'provider_id','id');
    }
}
