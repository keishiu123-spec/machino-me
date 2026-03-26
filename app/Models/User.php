<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar_url',
        'avatar',
        'bio',
        'organization_name',
        'my_school_id',
        'line_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAmbassador(): bool
    {
        return in_array($this->role, ['ambassador', 'admin']);
    }

    public function ambassadorPosts()
    {
        return $this->hasMany(AmbassadorPost::class);
    }

    public function managedSpots()
    {
        return $this->hasMany(Spot::class, 'ambassador_user_id');
    }

    public function mySchool()
    {
        return $this->belongsTo(School::class, 'my_school_id');
    }

    public function favoriteSpots()
    {
        return $this->belongsToMany(Spot::class, 'favorites')->withTimestamps();
    }

    public function ambassadorQuestions()
    {
        return $this->hasMany(AmbassadorQuestion::class, 'ambassador_user_id');
    }

    public function trialRequests()
    {
        return $this->hasMany(TrialRequest::class, 'ambassador_user_id');
    }

    public function getDisplayAvatarAttribute(): ?string
    {
        return $this->avatar ?: $this->avatar_url;
    }
}
