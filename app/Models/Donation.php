<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'donor_id',
        'project_id',
        'type',
        'amount',
        'purpose',
        'payment_method',
        'reference_number',
        'description',
        'donated_at',
    ];

    protected $casts = [
        'donated_at' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the registered member associated with the contribution.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the external donor associated with the donation.
     */
    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    /**
     * Get the target project associated with the donation or contribution.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}