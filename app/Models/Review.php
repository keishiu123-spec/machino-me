<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'spot_id',
        'user_id',
        'monthly_fee',
        'parent_duty',
        'strictness',
        'body',
        'satisfaction',
        'skill_growth',
        'cost_performance',
        'teacher_passion',
        'parent_burden',
        'vibe_tag',
    ];

    protected $casts = [
        'parent_duty' => 'boolean',
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
