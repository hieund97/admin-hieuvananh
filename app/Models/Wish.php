<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wish extends Model
{
    use HasFactory;

    protected $table = 'wish';

    protected $fillable = [
        'name',
        'wish_message',
        'wish_status',
        'wish_type',
    ];

    protected $casts = [
        'wish_status' => 'boolean',
    ];
}