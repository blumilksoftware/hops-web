<?php

declare(strict_types=1);

namespace Tests\Feature;

use HopsWeb\Models\Hop;
use HopsWeb\Models\HopQuery;
use HopsWeb\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComparisonInterfaceTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestIsRedirectedFromComparisonWorkspace(): void
    {
        $response = $this->get(route("comparison.index"));

        $response->assertRedirect(route("login"));
    }

    public function testAuthenticatedUserCanAccessComparisonWorkspace(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route("comparison.index"));

        $response->assertOk();
        $response->assertViewIs("comparison.index");
        $response->assertViewHas("history");
        $response->assertViewHas("activeQuery", null);
    }

    public function testUserCanSubmitNlpComparisonQuery(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route("comparison.store"), [
            "type" => "nlp",
            "natural_language_query" => "I want a citrusy hop like Citra",
        ]);

        $this->assertDatabaseHas("hop_queries", [
            "user_id" => $user->id,
        ]);

        $hopQuery = HopQuery::first();
        $this->assertNotNull($hopQuery);
        $this->assertEquals("I want a citrusy hop like Citra", $hopQuery->query["_nlp_query"]);
        $this->assertEquals(["Citra"], $hopQuery->query["target"]["present"]);

        $response->assertRedirect(route("comparison.index", ["history_id" => $hopQuery->id]));
    }

    public function testUserCanSubmitFormComparisonQuery(): void
    {
        $user = User::factory()->create();

        $queryPayload = [
            "target" => [
                "present" => ["Cascade", "Mosaic"],
            ],
            "aroma" => [
                "present" => ["citrusy", "fruity"],
            ],
            "ingredients" => [
                "alphas" => ["min" => 5.0, "max" => 12.0],
            ],
            "feeling" => [
                "bitterness" => "medium",
            ],
        ];

        $response = $this->actingAs($user)->post(route("comparison.store"), [
            "type" => "form",
            "query_json" => json_encode($queryPayload),
        ]);

        $this->assertDatabaseHas("hop_queries", [
            "user_id" => $user->id,
        ]);

        $hopQuery = HopQuery::first();
        $this->assertNotNull($hopQuery);
        $this->assertEquals(["Cascade", "Mosaic"], $hopQuery->query["target"]["present"]);
        $this->assertEquals(["citrusy", "fruity"], $hopQuery->query["aroma"]["present"]);
        $this->assertEquals(5.0, $hopQuery->query["ingredients"]["alphas"]["min"]);
        $this->assertEquals("medium", $hopQuery->query["feeling"]["bitterness"]);

        $response->assertRedirect(route("comparison.index", ["history_id" => $hopQuery->id]));
    }

    public function testUserCanLoadPastComparisonQueryFromHistory(): void
    {
        $user = User::factory()->create();

        Hop::factory()->create(["slug" => "cascade", "name" => "Cascade"]);
        Hop::factory()->create(["slug" => "centennial", "name" => "Centennial"]);

        $hopQuery = HopQuery::create([
            "user_id" => $user->id,
            "query" => [
                "target" => ["present" => ["Citra"]],
            ],
            "response" => [
                "results" => [
                    [
                        "hop_id" => "cascade",
                        "similarity_score" => 0.95,
                        "explainability" => ["aroma" => "High citrus"],
                    ],
                ],
                "metadata" => [
                    "execution_time_ms" => 120,
                    "modules_used" => ["target"],
                ],
            ],
        ]);

        $response = $this->actingAs($user)->get(route("comparison.index", ["history_id" => $hopQuery->id]));

        $response->assertOk();
        $response->assertViewHas("activeQuery");
        $activeQuery = $response->viewData("activeQuery");
        $this->assertEquals($hopQuery->id, $activeQuery->id);
        $response->assertViewHas("results");

        $results = $response->viewData("results");
        $this->assertCount(1, $results);
        $this->assertEquals("cascade", $results[0]["slug"]);
        $this->assertEquals(0.95, $results[0]["score"]);
        $this->assertNotNull($results[0]["hop"]);
        $this->assertEquals("Cascade", $results[0]["hop"]->name);
    }

    public function testComparisonQueryValidationProvidesReadableMessagesForNestedArrays(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route("comparison.store"), [
            "type" => "form",
            "query_json" => json_encode([
                "aroma" => [
                    "present" => ["invalid_aroma_1", "invalid_aroma_2"],
                ],
            ]),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors([
            "aroma.present.0" => "The aroma descriptor 'invalid_aroma_1' is invalid.",
            "aroma.present.1" => "The aroma descriptor 'invalid_aroma_2' is invalid.",
        ]);
    }

    public function testComparisonQueryValidationPreservesTabTypeInput(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route("comparison.store"), [
            "type" => "form",
            "query_json" => json_encode([
                "aroma" => [
                    "present" => ["invalid_aroma"],
                ],
            ]),
        ]);

        $response->assertRedirect();
        $this->assertEquals("form", session()->getOldInput("type"));
    }

    public function testComparisonQueryValidationFailsWhenRangeIngredientsAreEnabledWithoutValues(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route("comparison.store"), [
            "type" => "form",
            "query_json" => json_encode([
                "ingredients" => [
                    "alphas" => [
                        "min" => null,
                        "max" => null,
                    ],
                ],
            ]),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors([
            "ingredients.alphas" => "The alpha acids range min and max must be numeric.",
        ]);
    }
}
