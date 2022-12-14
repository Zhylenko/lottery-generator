<?php

namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryCode extends Model
{
    use HasFactory;

    protected $table = 'lottery_code';

    public function lottery()
    {
        return $this->belongsTo(Lottery::class);
    }

    public function code()
    {
        return $this->belongsTo(Code::class);
    }

    public function generated()
    {
        return $this->hasOne(LotteryCodeGenerated::class);
    }

    public function special()
    {
        return $this->hasOne(LotteryCodeSpecial::class);
    }
}
