<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'support_tickets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'subject',
        'status',
        'message_status'
    ];

    public function ticketUser()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\SupportTicketMessage', 'ticket_id')->orderBy('id', 'DESC');
    }

    public function get_date()
    {
        return Carbon::parse($this->messages->first()->created_at)->format('d M, Y G:i A');
    }

    public function status()
    {
        //0=pending;1=open;2=customer reply;3=admin reply;4=close
        if ($this->status == 0) {
            $status = '<span class="badge badge-warning">Pending</span>';
        } elseif ($this->status == 1) {
            $status = '<span class="badge badge-info">Open</span>';
            if ($this->message_status == 0) {
                $status .= '&nbsp;<span class="badge badge-success">User Replied</span>';
            } elseif ($this->message_status == 1) {
                $status .= '&nbsp;<span class="badge badge-success">Admin Replied</span>';
            }
        } elseif ($this->status == 2) {
            $status = '<span class="badge badge-danger">Closed</span>';
        }
        return $status;
    }
}
