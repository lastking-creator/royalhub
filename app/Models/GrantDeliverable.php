<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrantDeliverable extends Model
{
    protected $fillable = ['grant_id', 'title', 'due_date', 'status', 'notes'];

    protected $casts = ['due_date' => 'date'];

    public function grant() { return $this->belongsTo(Grant::class); }
}