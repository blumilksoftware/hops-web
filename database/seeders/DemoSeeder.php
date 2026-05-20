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
        Hop::factory()->create(["name" => "Cascade", "slug" => "cascade", "country" => "United States"]);
        Hop::factory()->create(["name" => "Centennial", "slug" => "centennial", "country" => "United States"]);
        Hop::factory()->create(["name" => "Columbus", "slug" => "columbus", "country" => "United States"]);

        Hop::factory(50)->create();
        User::factory(30)->create();

        User::factory(["name" => "Admin", "email" => "admin@example.com", "password" => "password"])->admin()->create();
        User::factory(["name" => "Team Member", "email" => "member@example.com", "password" => "password"])->teamMember()->create();

        HopQuery::factory(10)->withFullQuery()->for(User::first())->create();

        $users = User::inRandomOrder()->take(5)->get();

        foreach ($users as $user) {
            HopQuery::factory()->count(rand(2, 5))->withFullQuery()->for($user)->create();
        }
    }
}
