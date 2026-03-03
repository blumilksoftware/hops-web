<?php

declare(strict_types=1);

namespace Database\Factories;

use HopsWeb\Models\HopQuery;
use HopsWeb\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HopQuery>
 */
class HopQueryFactory extends Factory
{
    protected $model = HopQuery::class;

    public static function emptyQuery(): array
    {
        return [
            "target" => [
                "present" => [],
                "absent" => [],
            ],
            "aroma" => [
                "present" => [],
                "absent" => [],
            ],
            "description" => [
                "present" => [],
                "absent" => [],
            ],
            "ingredients" => self::nullIngredients(),
            "feeling" => [
                "bitterness" => null,
                "aromaticity" => null,
            ],
        ];
    }

    public static function fullQuery(): array
    {
        return [
            "target" => [
                "present" => ["Citra", "Mosaic", "Simcoe"],
                "absent" => ["Saaz", "Hallertau"],
            ],
            "aroma" => [
                "present" => ["citrusy", "fruity", "floral"],
                "absent" => ["spicy", "herbal"],
            ],
            "description" => [
                "present" => ["tropical", "juicy", "piney"],
                "absent" => ["earthy", "woody"],
            ],
            "ingredients" => [
                "alphas" => ["min" => 10.0, "max" => 14.0],
                "betas" => ["min" => 3.0, "max" => 5.0],
                "cohumulones" => ["min" => 20.0, "max" => 30.0],
                "polyphenols" => 4.5,
                "xanthohumol" => null,
                "oils" => ["min" => 1.5, "max" => 3.0],
                "farnesenes" => ["min" => 0.5, "max" => 5.0],
                "linalool" => null,
            ],
            "feeling" => [
                "bitterness" => "high",
                "aromaticity" => "high",
            ],
        ];
    }

    public static function nullIngredients(): array
    {
        return [
            "alphas" => null,
            "betas" => null,
            "cohumulones" => null,
            "polyphenols" => null,
            "xanthohumol" => null,
            "oils" => null,
            "farnesenes" => null,
            "linalool" => null,
        ];
    }

    public function definition(): array
    {
        return [
            "user_id" => User::factory(),
            "query" => self::emptyQuery(),
            "response" => null,
        ];
    }

    public function withFullQuery(): static
    {
        return $this->state(fn(): array => [
            "query" => self::fullQuery(),
        ]);
    }

    public function withQuery(array $query): static
    {
        return $this->state(fn(): array => [
            "query" => array_replace_recursive(self::emptyQuery(), $query),
        ]);
    }

    public function withResponse(array $response): static
    {
        return $this->state(fn(): array => [
            "response" => $response,
        ]);
    }
}
