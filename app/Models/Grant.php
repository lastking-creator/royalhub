<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grant extends Model
{
    protected $fillable = ['funder_name', 'title', 'amount', 'application_deadline', 'start_date', 'end_date', 'status'];

    protected $casts = [
        'application_deadline' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function deliverables() { return $this->hasMany(GrantDeliverable::class); }
    public function documents() { return $this->hasMany(GrantDocument::class); }
}