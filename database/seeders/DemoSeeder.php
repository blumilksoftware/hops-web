<?php

declare(strict_types=1);

namespace Database\Seeders;

use HopsWeb\Models\Hop;
use HopsWeb\Models\HopQuery;
use HopsWeb\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        Hop::factory(50)->create();
        User::factory(30)->create();

        User::factory(["name" => "Admin", "email" => "admin@example.com", "password" => "password"])->admin()->create();
        User::factory(["name" => "Team Member", "email" => "member@example.com", "password" => "password"])->teamMember()->create();

        HopQuery::factory(10)->for(User::first())->create();
    }
}
