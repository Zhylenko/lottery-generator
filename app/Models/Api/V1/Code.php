<?php

namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $casts = [
        'code' => 'array',
    ];

    protected $fillable = [
        'code',
    ];
}
