<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'recipient_group', 'recipient_contact', 'subject', 'message', 'status', 'sender_id'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
