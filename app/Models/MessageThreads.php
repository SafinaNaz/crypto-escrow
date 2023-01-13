<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageThreads extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'message_threads';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'seller_id',
        'buyer_id',
        'product_id',
        'max_message',
        'is_read'
    ];

    public function threadUser()
    {
        return $this->belongsTo('App\Models\User', 'seller_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\Messages', 'thread_id')->orderBy('id', 'ASC');
    }

    public function product()
    {
        return $this->hasOne('App\Models\EscrowProducts', 'id', 'product_id');
    }
}
