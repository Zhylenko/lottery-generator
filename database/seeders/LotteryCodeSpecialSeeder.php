<?php

namespace Database\Seeders;

use App\Models\Api\V1\Lottery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LotteryCodeSpecialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lottery::factory(3)->create()->each(function (Lottery $lottery) {
            for ($i = 0; $i < 10; $i++) {
                \LotteryCodeService::storeLotteryCode($lottery, \LotteryCodeService::generateLotteryCode($lottery, true), false)
                    ->special()
                    ->create();
            }
        });
    }
}
