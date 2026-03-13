<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmbassadorAnswer extends Model
{
    protected $fillable = ['ambassador_question_id', 'user_id', 'body'];

    public function question()
    {
        return $this->belongsTo(AmbassadorQuestion::class, 'ambassador_question_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
