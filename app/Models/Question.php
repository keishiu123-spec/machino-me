<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'note',
        'category',
        'image_path',
        'lat',
        'lng',
        'user_id',
        'spot_id',
        'target_age',
        'status',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
