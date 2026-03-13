<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmbassadorQuestion extends Model
{
    protected $fillable = ['ambassador_user_id', 'user_id', 'body'];

    public function ambassador()
    {
        return $this->belongsTo(User::class, 'ambassador_user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(AmbassadorAnswer::class);
    }
}
