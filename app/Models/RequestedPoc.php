<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestedPoc extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'currency_id',
        'poc_amount',
        'reference_no',
        'admin_reference_no',
        'poc_percentage',
        'status',
        'sender_wallet_address',
        'receiver_wallet_address',
    ];
}
