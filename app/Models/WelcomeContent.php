<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WelcomeContent extends Model
{
    protected $fillable = [
    'title',
    'subtitle',
    'body_content',
    'mission',
    'vision',
    'hero_image',
];
}
