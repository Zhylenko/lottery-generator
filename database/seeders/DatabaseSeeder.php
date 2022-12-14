<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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
            SpecialCodeSeeder::class,
        ]);
    }
}
