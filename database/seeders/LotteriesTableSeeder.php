<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LotteriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('lotteries')->delete();
        
        \DB::table('lotteries')->insert(array (
            0 => 
            array (
                'name' => '5/35',
                'numbers_count' => 5,
                'numbers_from' => 1,
                'numbers_to' => 35,
                'created_at' => '2022-12-16 12:20:43',
                'updated_at' => '2022-12-16 12:20:43',
            ),
            1 => 
            array (
                'name' => '5/42',
                'numbers_count' => 5,
                'numbers_from' => 1,
                'numbers_to' => 42,
                'created_at' => '2022-12-16 12:20:57',
                'updated_at' => '2022-12-16 12:20:57',
            ),
            2 => 
            array (
                'name' => '6/49',
                'numbers_count' => 6,
                'numbers_from' => 1,
                'numbers_to' => 49,
                'created_at' => '2022-12-16 12:21:31',
                'updated_at' => '2022-12-16 12:21:31',
            ),
        ));
        
        
    }
}