<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrialRequest extends Model
{
    protected $fillable = [
        'ambassador_user_id', 'spot_id', 'user_id',
        'parent_name', 'child_name', 'child_age',
        'phone', 'email', 'note', 'status',
    ];

    public function ambassador()
    {
        return $this->belongsTo(User::class, 'ambassador_user_id');
    }

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
