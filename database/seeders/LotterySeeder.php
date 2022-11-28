<?php

namespace Database\Seeders;

use App\Models\Api\V1\Lottery;
use App\Models\Api\V1\LotteryCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LotterySeeder extends Seeder
{
    protected $lotteries = [
        [
            'name' => '5/35',
            'numbers_count' => 5,
            'numbers_from' => 1,
            'numbers_to' => 35
        ],
        [
            'name' => '5/42',
            'numbers_count' => 5,
            'numbers_from' => 1,
            'numbers_to' => 42
        ],
        [
            'name' => '6/49',
            'numbers_count' => 6,
            'numbers_from' => 1,
            'numbers_to' => 49
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->lotteries as $lottery) {
            Lottery::create([
                'name' => $lottery['name'],
                'numbers_count' => $lottery['numbers_count'],
                'numbers_from' => $lottery['numbers_from'],
                'numbers_to' => $lottery['numbers_to']
            ]);
        }

        Lottery::factory(100)->create()->each(function (Lottery $lottery) {
            $lottery->codes()->saveMany(LotteryCode::factory(2)->make());
        });
    }
}
