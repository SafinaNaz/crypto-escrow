<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscrowProducts extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'escrow_products';
    protected $with = ['threads'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'seller_id',
        'buying_selling_option',
        'product_name',
        'buyer_username',
        'currency_id',
        'price',
        'total_price',
        'commission',
        'encrypted_text',
        'non_encrypted_text',
        'escrow_fee_payer',
        'completion_time',
        'completion_days',
        'status',
        'buyer_id',
        'transaction_id',
        'wallet_address',
        'term_conditions',
        'immediate_release'
    ];

    public function completion_days()
    {
        if ($this->immediate_release == 1) {
            return 'Immediate Release';
        } else {
            return $this->completion_days . ' Days';
        }
    }

    public function seller()
    {
        return $this->belongsTo('App\Models\User', 'seller_id');
    }
    public function buyer()
    {
        return $this->belongsTo('App\Models\User', 'buyer_id');
    }

    public function threads()
    {
        return $this->hasOne('App\Models\MessageThreads', 'product_id', 'id');
    }

    public function review()
    {
        return $this->hasMany('App\Models\Review', 'product_id', 'id')->where('admin_review', 0);
    }
    public function admin_review()
    {
        return $this->hasMany('App\Models\Review', 'product_id', 'id')->where('admin_review', 1);
    }

    public function productCurrency()
    {
        return $this->hasOne('App\Models\Currency', 'id', 'currency_id');
    }
    public function productTransaction()
    {
        return $this->hasOne('App\Models\Transaction', 'product_id', 'id');
    }
    public function disputeTransaction()
    {
        return $this->hasOne('App\Models\DisputeTransaction', 'product_id', 'id');
    }

    public function pocRequest()
    {
        return $this->hasOne('App\Models\RequestedPoc', 'product_id', 'id');
    }
}
