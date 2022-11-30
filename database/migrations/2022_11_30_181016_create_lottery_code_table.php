<?php

use App\Models\Api\V1\Code;
use App\Models\Api\V1\Lottery;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lottery_code', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Lottery::class);
            $table->foreignIdFor(Code::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lottery_code');
    }
};
