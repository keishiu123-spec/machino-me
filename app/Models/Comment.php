<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * 一括割り当て可能な属性
     */
    protected $fillable = [
        'question_id',
        'user_id',
        'body',
        'thanks_count',
    ];

    /**
     * このコメントは特定の質問に属します
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}