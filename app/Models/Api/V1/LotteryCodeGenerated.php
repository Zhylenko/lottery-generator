<?php

namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryCodeGenerated extends Model
{
    use HasFactory;

    protected $table = 'lottery_code_generated';

    public function lotteryCode()
    {
        return $this->belongsTo(LotteryCode::class);
    }
}
