<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            LotterySeeder::class,
            CodeSeeder::class,
            LotteryCodeSeeder::class,
            LotteryCodeSpecialSeeder::class,
        ]);
    }
}
