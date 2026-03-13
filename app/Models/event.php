<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'organizer_name',
        'location_name',
        'lat',
        'lng',
        'image_path',
        'link_url',
        'user_id',
        'category_tags',
        'target_age',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'category_tags' => 'array',
    ];
}