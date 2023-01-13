<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicketMessage extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'support_ticket_messages';
    protected $with = ['user'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'files',
        'is_admin'
    ];

    public function tickets()
    {
        return $this->belongsTo('App\Models\SupportTicket', 'ticket_id', 'id');
    }
    public function get_date()
    {
        return Carbon::parse($this->created_at)->format('d M, Y G:i A');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
