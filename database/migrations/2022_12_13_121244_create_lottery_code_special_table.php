<?php

use App\Models\Api\V1\LotteryCode;
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
        Schema::create('lottery_code_special', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(LotteryCode::class)
                ->references('id')->on('lottery_code')
                ->onDelete('cascade');
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
        Schema::dropIfExists('lottery_code_special');
    }
};
