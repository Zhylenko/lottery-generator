<?php

namespace Database\Seeders;

use App\Models\Api\V1\Code;
use App\Models\Api\V1\Lottery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LotteryCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lottery::factory(3)->create()->each(function (Lottery $lottery) {
            for ($i = 0; $i < 50; $i++) {
                $lottery->codes()->create([
                    'code' => \LotteryCodeService::generateLotteryCode($lottery, true),
                ]);
            }

            // $lottery->codes()->saveMany(Code::factory(50)->make());
        });
    }
}
