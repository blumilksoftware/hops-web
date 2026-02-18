<?php

declare(strict_types=1);

namespace Database\Seeders;

use HopsWeb\Models\Hop;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        Hop::factory(50)->create();
    }
}
