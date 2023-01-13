<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'reviews';

    /* The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rating',
        'review',
        'is_active',
        'product_id',
        'review_to',
        'reviewer_id',
        'admin_review'
    ];

    public function product()
    {
        return $this->hasOne('App\Models\EscrowProducts', 'id', 'product_id');
    }

    public function review_to_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'review_to');
    }
    public function reviewer()
    {
        return $this->hasOne('App\Models\User', 'id', 'reviewer_id');
    }
}
