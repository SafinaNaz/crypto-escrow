<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'site_settings';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site_logo',
        'site_name',
        'site_title',
        'site_keywords',
        'site_description',
        'site_email',
        'inquiry_email',
        'site_phone',
        'site_mobile',
        'site_address',
        'facebook',
        'twitter',
        'linkedin',
        'insta',
        'skype',
        'escrow_fee_btc',
        'escrow_fee_monero',
        'btc_address',
        'monero_address',
        'is_mainnet',
        'level1_time',
        'level2_time',
        'level3_time',
        'site_announcement',
        'show_site_announcement',
        'seller_announcement',
        'show_seller_announcement',
        'buyer_announcement',
        'show_buyer_announcement',
        'immediate_release_hours',
        'deposit_amount',
    ];
}
