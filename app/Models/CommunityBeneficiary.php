<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityBeneficiary extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'location'];

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'community_beneficiary_program');
    }
}