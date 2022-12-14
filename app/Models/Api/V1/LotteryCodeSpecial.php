<?php

namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryCodeSpecial extends Model
{
    use HasFactory;

    protected $table = 'lottery_code_special';

    public function lotteryCode()
    {
        return $this->belongsTo(LotteryCode::class);
    }
}
