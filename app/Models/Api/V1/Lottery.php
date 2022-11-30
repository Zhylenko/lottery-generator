<?php

namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lottery extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'numbers_count',
        'numbers_from',
        'numbers_to',
    ];
}
