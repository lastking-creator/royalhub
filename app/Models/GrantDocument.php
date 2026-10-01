<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrantDocument extends Model
{
    protected $fillable = ['grant_id', 'title', 'category', 'file_path'];

    public function grant() { return $this->belongsTo(Grant::class); }
}