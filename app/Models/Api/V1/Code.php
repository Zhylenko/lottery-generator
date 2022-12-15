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

    public function lotteries()
    {
        return $this->belongsToMany(Lottery::class, 'lottery_code')->withTimestamps();
    }

    public function special()
    {
        return $this->hasOneThrough(LotteryCodeSpecial::class, LotteryCode::class);
    }
}
