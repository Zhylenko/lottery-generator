<?php

namespace Database\Seeders;

use App\Models\Api\V1\LotteryCode;
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
        LotteryCode::factory(100)->create();
    }
}
