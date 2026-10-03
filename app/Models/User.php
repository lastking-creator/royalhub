<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name',
    'email',
    'password',
    'status', // Added status column
    'registration_number',
    'phone_number',
    'role',
    'date_of_birth',
    'id_number',
    'physical_address',
    'whatsapp_number',
    'next_of_kin_name',
    'next_of_kin_relationship',
    'next_of_kin_phone',
    'occupation',
    'talents',
    'talents_skills',
    'passport_photo',
    'terms_accepted',
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function programs()
{
    return $this->belongsToMany(Program::class, 'program_beneficiary');
}
}