<?php

namespace Database\Seeders;

use App\Models\Api\V1\Lottery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeedFromCsvSeeder extends Seeder
{
    protected $lotteries = [
        [
            'name' => '5/35',
            'numbers_count' => 5,
            'numbers_from' => 1,
            'numbers_to' => 35,

            'codes' => __DIR__ . '/csv/5x35_codes.csv',
            'codes_special' => __DIR__ . '/csv/5x35_codes_special.csv',
        ],
        [
            'name' => '5/42',
            'numbers_count' => 5,
            'numbers_from' => 1,
            'numbers_to' => 42,

            'codes' => __DIR__ . '/csv/5x42_codes.csv',
            'codes_special' => __DIR__ . '/csv/5x42_codes_special.csv',
        ],
        [
            'name' => '6/49',
            'numbers_count' => 6,
            'numbers_from' => 1,
            'numbers_to' => 49,

            'codes' => __DIR__ . '/csv/6x49_codes.csv',
            'codes_special' => __DIR__ . '/csv/6x49_codes_special.csv',
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->lotteries as $lotteryData) {
            $lottery = Lottery::create([
                'name' => $lotteryData['name'],
                'numbers_count' => $lotteryData['numbers_count'],
                'numbers_from' => $lotteryData['numbers_from'],
                'numbers_to' => $lotteryData['numbers_to']
            ]);

            $row = 1;

            if (($handle = fopen($lotteryData['codes'], "r")) !== false) {
                while (($data = fgetcsv($handle)) !== false) {
                    $code = json_decode($data[0], true);

                    if ($code)
                        \LotteryCodeService::storeLotteryCode($lottery, $code, false);

                    $row++;
                }
                fclose($handle);
            }

            $row = 1;

            if (($handle = fopen($lotteryData['codes_special'], "r")) !== false) {
                while (($data = fgetcsv($handle)) !== false) {
                    $code = json_decode($data[0], true);

                    if ($code)
                        \LotteryCodeService::storeLotteryCode($lottery, $code, false)
                            ->special()
                            ->create();

                    $row++;
                }
                fclose($handle);
            }
        }
    }
}
