<?php

namespace Tests;

use Database\Seeders\DatabaseTestSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected
        $seed = true,
        $seeder = DatabaseTestSeeder::class;
}
