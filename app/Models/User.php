<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasRoles, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_type',
        'username',
        'email_verified_at',
        'password',

        'wallet_type',
        'btc_address',
        'monero_address',
        'approved_status',
        'last_login_on',
        'is_active',
        'google2fa_secret',
        'etl_information',
        'etl_images'
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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function escrow_products()
    {
        return $this->hasMany('App\Models\EscrowProducts', 'seller_id');
    }

    public function full_name()
    {
        $tick = '';
        if ($this->approved_status == 1) {
            $tick = '<i class="fa fa-check verifiedTick"></i>';
        }
        return ucwords($this->username) . ' ' . $tick;
    }

    public function photo()
    {
        $user = $this;
        if ($user->user_type == 1) {
            return asset('frontend/dashboard/images/user-thumb-seller.png');
        } else {
            return asset('frontend/dashboard/images/user-thumb-buyer.png');
        }
    }
    public function reviews()
    {
        return $this->hasMany('App\Models\Review','review_to', 'id');
    }

    public function getAvgRatingAttribute(){
        $reviews = $this->reviews;
        // dd($reviews);
        $total_reviews = count($reviews);
        if($total_reviews == 0 ){
            return 5;
        }else{

            return number_format($reviews->sum('rating') / $total_reviews, 2);

        }
    }
    public function getTotalSellingsAttribute(){
        $data['bitcoin'] = EscrowProducts::where('seller_id', $this->id)->where('currency_id',1)->sum('total_price');
        $data['monero'] = EscrowProducts::where('seller_id', $this->id)->where('currency_id',2)->sum('total_price');
        return $data;
    }
    public function getTotalBuyingsAttribute(){
        $data['bitcoin'] = EscrowProducts::where('buyer_id', $this->id)->where('currency_id',1)->sum('total_price');
        $data['monero'] = EscrowProducts::where('buyer_id', $this->id)->where('currency_id',2)->sum('total_price');
        return $data;
    }
}
