<?php

declare(strict_types=1);

namespace Database\Seeders;

use HopsWeb\Models\Agenda;
use HopsWeb\Models\AgendaResult;
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
        $teamMember = User::factory(["name" => "Team Member", "email" => "member@example.com", "password" => "password"])->teamMember()->create();

        $agendas = [
            [
                "name" => "Citrus & Tropical Hop Assessment",
                "query" => [
                    "target" => ["present" => ["Citra", "Mosaic", "Simcoe"], "absent" => ["Saaz"]],
                    "aroma" => ["present" => ["citrusy", "fruity", "floral"], "absent" => ["herbal"]],
                    "feeling" => ["bitterness" => "high", "aromaticity" => "high"],
                ],
            ],
            [
                "name" => "Noble Hop Substitute Search",
                "query" => [
                    "target" => ["present" => ["Saaz", "Hallertau"], "absent" => ["Citra"]],
                    "aroma" => ["present" => ["spicy", "herbal", "earthy"], "absent" => ["citrusy"]],
                    "feeling" => ["bitterness" => "low", "aromaticity" => "medium"],
                ],
            ],
            [
                "name" => "High Alpha Acid Verification",
                "query" => [
                    "target" => ["present" => ["Columbus", "Centennial"]],
                    "ingredients" => ["alphas" => ["min" => 12.0, "max" => 16.0]],
                    "feeling" => ["bitterness" => "high"],
                ],
            ],
        ];

        foreach ($agendas as $agendaData) {
            $agenda = Agenda::create([
                "user_id" => $teamMember->id,
                "name" => $agendaData["name"],
                "query" => $agendaData["query"],
            ]);

            $runsCount = rand(1, 3);

            for ($runIndex = 0; $runIndex < $runsCount; $runIndex++) {
                $weightAroma = round(rand(25, 45) / 100, 2);
                $weightBiochemical = round(rand(20, 35) / 100, 2);
                $weightDescription = round(rand(15, 25) / 100, 2);
                $weightFeeling = round(1.0 - ($weightAroma + $weightBiochemical + $weightDescription), 2);

                AgendaResult::create([
                    "agenda_id" => $agenda->id,
                    "parameters" => [
                        "weights" => [
                            "aroma" => $weightAroma,
                            "biochemical" => $weightBiochemical,
                            "description" => $weightDescription,
                            "feeling" => $weightFeeling,
                        ],
                    ],
                    "response" => [
                        ["id" => 1, "name" => "Citra", "score" => round(rand(80, 98) / 100, 2)],
                        ["id" => 2, "name" => "Mosaic", "score" => round(rand(70, 85) / 100, 2)],
                        ["id" => 3, "name" => "Cascade", "score" => round(rand(60, 75) / 100, 2)],
                    ],
                ]);
            }
        }

        HopQuery::factory(10)->withFullQuery()->for(User::first())->create();

        $users = User::inRandomOrder()->take(5)->get();

        foreach ($users as $user) {
            HopQuery::factory()->count(rand(2, 5))->withFullQuery()->for($user)->create();
        }
    }
}
