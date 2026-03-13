<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmbassadorPost extends Model
{
    protected $fillable = [
        'user_id',
        'spot_id',
        'photo_path',
        'message',
        'mood_tag',
        'has_osagari',
        'osagari_item',
        'osagari_size',
    ];

    protected function casts(): array
    {
        return [
            'has_osagari' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
