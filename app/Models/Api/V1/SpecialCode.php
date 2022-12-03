<?php

namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialCode extends Model
{
    use HasFactory;

    public function code()
    {
        return $this->belongsTo(Code::class);
    }
}
