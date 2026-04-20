<?php

declare(strict_types=1);

namespace Tests\Feature;

use HopsWeb\Enums\Aromaticity;
use HopsWeb\Enums\Bitterness;
use HopsWeb\Http\Requests\ComparisonQueryRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ComparisonQueryValidationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::post("/api/compare", fn(ComparisonQueryRequest $request) => Response::json(["status" => "success"]))->middleware("api");
    }

    public function testTargetFieldPassesWithValidData(): void
    {
        $response = $this->postJson("/api/compare", [
            "target" => [
                "present" => ["Cascade"],
                "absent" => ["Citra"],
            ],
        ]);

        $response->assertStatus(200)->assertJson(["status" => "success"]);
    }

    public function testTargetFieldFailsWhenDuplicatesExist(): void
    {
        $response = $this->postJson("/api/compare", [
            "target" => [
                "present" => ["Cascade", "Citra"],
                "absent" => ["Citra", "Mosaic"],
            ],
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(["target"]);
    }

    public function testIngredientsFieldPassesWithValidNumberAndRange(): void
    {
        $response = $this->postJson("/api/compare", [
            "ingredients" => [
                "alphas" => 5.5,
                "betas" => ["min" => 2, "max" => 4.5],
                "oils" => null,
            ],
        ]);

        $response->assertStatus(200)->assertJson(["status" => "success"]);
    }

    public function testIngredientsFieldFailsWithInvalidRange(): void
    {
        $response = $this->postJson("/api/compare", [
            "ingredients" => [
                "alphas" => ["min" => 5, "max" => 2],
            ],
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(["ingredients.alphas"]);
    }

    public function testFeelingFieldPassesWithValidEnums(): void
    {
        $response = $this->postJson("/api/compare", [
            "feeling" => [
                "bitterness" => Bitterness::High->value,
                "aromaticity" => Aromaticity::Medium->value,
            ],
        ]);

        $response->assertStatus(200)->assertJson(["status" => "success"]);
    }

    public function testFeelingFieldFailsWithInvalidEnum(): void
    {
        $response = $this->postJson("/api/compare", [
            "feeling" => [
                "bitterness" => "very-very-high",
            ],
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(["feeling.bitterness"]);
    }

    public function testFailsWhenUnknownTopLevelKeysAreProvided(): void
    {
        $response = $this->postJson("/api/compare", [
            "feeling" => ["bitterness" => "low"],
            "unknown_key" => "some_value",
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(["query"]);
    }
}
