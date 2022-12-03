<?php

namespace Database\Seeders;

use App\Models\Api\V1\Code;
use App\Models\Api\V1\SpecialCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Code::factory(10)
            ->has(SpecialCode::factory(), 'special')
            ->create();
    }
}
