<?php

namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryCode extends Model
{
    use HasFactory;

    protected $casts = [
        'code' => 'array',
    ];

    public function lottery()
    {
        return $this->belongsTo(Lottery::class);
    }
}
