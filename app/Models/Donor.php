<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'organization',
        'email',
        'phone',
    ];

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }
}