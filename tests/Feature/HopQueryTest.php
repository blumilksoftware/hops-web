<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Factories\HopQueryFactory;
use HopsWeb\Models\HopQuery;
use HopsWeb\Models\User;
use Tests\TestCase;

class HopQueryTest extends TestCase
{
    public function testHopQueryCanBeCreatedWithFullQuery(): void
    {
        $user = User::factory()->create();
        $fullQuery = HopQueryFactory::fullQuery();

        $hopQuery = HopQuery::factory()
            ->for($user)
            ->withFullQuery()
            ->create();

        $this->assertDatabaseHas("hop_queries", [
            "id" => $hopQuery->id,
            "user_id" => $user->id,
        ]);

        $freshQuery = $hopQuery->fresh();

        $this->assertIsArray($freshQuery->query);
        $this->assertEquals($fullQuery, $freshQuery->query);

        $this->assertEquals(["Citra", "Mosaic", "Simcoe"], $freshQuery->query["target"]["present"]);
        $this->assertEquals(["Saaz", "Hallertau"], $freshQuery->query["target"]["absent"]);

        $this->assertEquals(["citrusy", "fruity", "floral"], $freshQuery->query["aroma"]["present"]);
        $this->assertEquals(["spicy", "herbal"], $freshQuery->query["aroma"]["absent"]);

        $this->assertEquals(["tropical", "juicy", "piney"], $freshQuery->query["description"]["present"]);
        $this->assertEquals(["earthy", "woody"], $freshQuery->query["description"]["absent"]);

        $this->assertEquals(["min" => 10.0, "max" => 14.0], $freshQuery->query["ingredients"]["alphas"]);
        $this->assertEquals(4.5, $freshQuery->query["ingredients"]["polyphenols"]);
        $this->assertNull($freshQuery->query["ingredients"]["xanthohumol"]);
        $this->assertNull($freshQuery->query["ingredients"]["linalool"]);

        $this->assertEquals("high", $freshQuery->query["feeling"]["bitterness"]);
        $this->assertEquals("high", $freshQuery->query["feeling"]["aromaticity"]);
    }

    public function testHopQueryCanBeCreatedWithEmptyLists(): void
    {
        $emptyQuery = HopQueryFactory::emptyQuery();

        $hopQuery = HopQuery::factory()->create();

        $freshQuery = $hopQuery->fresh();

        $this->assertIsArray($freshQuery->query);
        $this->assertEquals($emptyQuery, $freshQuery->query);
    }

    public function testHopQueryCanBeCreatedWithPartialQuery(): void
    {
        $hopQuery = HopQuery::factory()
            ->withQuery([
                "target" => [
                    "present" => ["Citra"],
                ],
                "aroma" => [
                    "present" => ["citrusy"],
                ],
                "ingredients" => [
                    "alphas" => 12.5,
                ],
                "feeling" => [
                    "bitterness" => "medium",
                ],
            ])
            ->create();

        $freshQuery = $hopQuery->fresh();

        $this->assertEquals(["Citra"], $freshQuery->query["target"]["present"]);
        $this->assertEmpty($freshQuery->query["target"]["absent"]);
        $this->assertEquals(12.5, $freshQuery->query["ingredients"]["alphas"]);
        $this->assertEquals("medium", $freshQuery->query["feeling"]["bitterness"]);
        $this->assertNull($freshQuery->query["feeling"]["aromaticity"]);
    }

    public function testHopQueryCanStoreResponse(): void
    {
        $response = [
            ["id" => 1, "name" => "Citra", "score" => 0.95],
            ["id" => 2, "name" => "Mosaic", "score" => 0.85],
            ["id" => 3, "name" => "Simcoe", "score" => 0.78],
        ];

        $hopQuery = HopQuery::factory()
            ->withQuery([
                "target" => ["present" => ["Citra"]],
            ])
            ->withResponse($response)
            ->create();

        $freshQuery = $hopQuery->fresh();

        $this->assertIsArray($freshQuery->response);
        $this->assertCount(3, $freshQuery->response);
        $this->assertEquals("Citra", $freshQuery->response[0]["name"]);
        $this->assertEquals(0.95, $freshQuery->response[0]["score"]);
    }

    public function testHopQueryBelongsToUser(): void
    {
        $user = User::factory()->create();

        $hopQuery = HopQuery::factory()
            ->for($user)
            ->create();

        $this->assertInstanceOf(User::class, $hopQuery->user);
        $this->assertEquals($user->id, $hopQuery->user->id);
    }

    public function testHopQueryWithAllFeelingValues(): void
    {
        $user = User::factory()->create();
        $feelingValues = ["low", "medium", "high"];

        foreach ($feelingValues as $bitterness) {
            foreach ($feelingValues as $aromaticity) {
                $hopQuery = HopQuery::factory()
                    ->for($user)
                    ->withQuery([
                        "feeling" => [
                            "bitterness" => $bitterness,
                            "aromaticity" => $aromaticity,
                        ],
                    ])
                    ->create();

                $freshQuery = $hopQuery->fresh();
                $this->assertEquals($bitterness, $freshQuery->query["feeling"]["bitterness"]);
                $this->assertEquals($aromaticity, $freshQuery->query["feeling"]["aromaticity"]);
            }
        }
    }

    public function testHopQueryWithAllAromaTypes(): void
    {
        $allAromas = [
            "citrusy",
            "fruity",
            "floral",
            "herbal",
            "spicy",
            "resinous",
            "sugarlike",
            "miscellaneous",
        ];

        $hopQuery = HopQuery::factory()
            ->withQuery([
                "aroma" => [
                    "present" => $allAromas,
                ],
            ])
            ->create();

        $freshQuery = $hopQuery->fresh();
        $this->assertEquals($allAromas, $freshQuery->query["aroma"]["present"]);
        $this->assertCount(8, $freshQuery->query["aroma"]["present"]);
    }

    public function testHopQueryIngredientsWithMixedRangesAndNumbers(): void
    {
        $hopQuery = HopQuery::factory()
            ->withQuery([
                "ingredients" => [
                    "alphas" => ["min" => 5.0, "max" => 12.0],
                    "betas" => 4.5,
                    "cohumulones" => ["min" => 20.0, "max" => 35.0],
                    "xanthohumol" => 0.3,
                    "oils" => ["min" => 1.0, "max" => 2.5],
                    "linalool" => 0.8,
                ],
                "feeling" => [
                    "bitterness" => "low",
                    "aromaticity" => "high",
                ],
            ])
            ->create();

        $freshQuery = $hopQuery->fresh();

        $this->assertEquals(["min" => 5.0, "max" => 12.0], $freshQuery->query["ingredients"]["alphas"]);
        $this->assertEquals(["min" => 20.0, "max" => 35.0], $freshQuery->query["ingredients"]["cohumulones"]);
        $this->assertEquals(["min" => 1.0, "max" => 2.5], $freshQuery->query["ingredients"]["oils"]);

        $this->assertEquals(4.5, $freshQuery->query["ingredients"]["betas"]);
        $this->assertEquals(0.3, $freshQuery->query["ingredients"]["xanthohumol"]);
        $this->assertEquals(0.8, $freshQuery->query["ingredients"]["linalool"]);

        $this->assertNull($freshQuery->query["ingredients"]["polyphenols"]);
        $this->assertNull($freshQuery->query["ingredients"]["farnesenes"]);
    }

    public function testUserCanHaveMultipleHopQueries(): void
    {
        $user = User::factory()->create();

        HopQuery::factory()->for($user)->count(3)->create();

        $this->assertCount(3, HopQuery::where("user_id", $user->id)->get());
    }

    public function testHopQueryIsDeletedWhenUserIsDeleted(): void
    {
        $user = User::factory()->create();

        $hopQuery = HopQuery::factory()
            ->for($user)
            ->create();

        $queryId = $hopQuery->id;

        $user->delete();

        $this->assertDatabaseMissing("hop_queries", ["id" => $queryId]);
    }
}
