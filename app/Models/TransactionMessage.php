<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionMessage extends Model
{
    use HasFactory;
     protected $fillable = [
        'seller_id',
        'buyer_id',
        'admin_id',
        'product_id',
        'transaction_type',
        'message',
    ];
}
