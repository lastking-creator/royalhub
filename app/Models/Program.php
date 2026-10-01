<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'category',
        'description',
        'status',
        'timeline',
        'budget'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function beneficiaries()
    {
        return $this->belongsToMany(User::class, 'program_beneficiary');
    }

    public function communityBeneficiaries()
    {
        return $this->belongsToMany(CommunityBeneficiary::class, 'community_beneficiary_program');
    }
}