<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $with = ['transactionStatus'];

    protected $fillable = [
        'product_id',
        'currency_id',
        'total_amount',
        'reference_no',
        'admin_reference_no',
        'commission',
        'status_id',
        'cancelled_by',
        'sender_wallet_address',
        'receiver_wallet_address',
        'admin_sender_wallet_address',
        'admin_wallet_address',
    ];

    public function transactionCurrency()
    {
        return $this->hasOne('App\Models\Currency', 'id', 'currency_id');
    }

    public function transactionStatus()
    {
        return $this->hasOne(TransactionStatus::class, 'id', 'status_id');
    }
}
