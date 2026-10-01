<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'type', 'description', 'location', 'start_time', 'end_time', 'capacity'];

    public function attendances()
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_attendances')->withPivot('rsvp_status', 'checked_in', 'checked_in_at');
    }
}
