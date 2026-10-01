<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ComplianceDocument extends Model
{
    protected $fillable = ['title', 'category', 'file_path', 'version', 'expires_at', 'uploaded_by'];

    protected $casts = [
        'expires_at' => 'date',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Expiry Status Helper
    public function getExpiryStatusAttribute()
    {
        if (!$this->expires_at) {
            return 'no_expiry';
        }

        $days = Carbon::now()->diffInDays($this->expires_at, false);

        if ($days < 0) {
            return 'expired';
        } elseif ($days <= 30) {
            return 'expiring_soon';
        }

        return 'valid';
    }
}