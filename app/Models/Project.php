<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'target_community',
        'manager_id',
        'budget',
        'start_date',
        'end_date',
        'description',
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    // Direct relationship to track all assigned donations/grants
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    // Accessor for total raised funds for this project
    public function getTotalRaisedAttribute()
    {
        return $this->donations()->sum('amount');
    }
}