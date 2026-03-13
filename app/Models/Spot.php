<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'lat',
        'lng',
        'category',
        'note',
        'link_url',
        'google_place_id',
        'user_id',
        'image_path',
        'age_range',
        'parent_role',
        'category_tags',
        'monthly_fee_range',
        'has_parent_duty',
        'policy_type',
        'transfer_available',
        'ambassador_user_id',
    ];

    protected $casts = [
        'category_tags' => 'array',
        'has_parent_duty' => 'boolean',
        'transfer_available' => 'boolean',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ambassador()
    {
        return $this->belongsTo(User::class, 'ambassador_user_id');
    }

    public function ambassadorPosts()
    {
        return $this->hasMany(AmbassadorPost::class);
    }

    public function latestAmbassadorPost()
    {
        return $this->hasOne(AmbassadorPost::class)->latestOfMany();
    }
}