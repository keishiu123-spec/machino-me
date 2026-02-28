<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    // ↓ ここを追記（保存を許可するカラムを指定）
    protected $fillable = [
        'lat',
        'lng',
        'category',
        'note',
        'user_id',
    ];
}