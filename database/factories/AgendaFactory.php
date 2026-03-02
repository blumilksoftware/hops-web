<?php

declare(strict_types=1);

namespace Database\Factories;

use HopsWeb\Models\Agenda;
use HopsWeb\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Agenda>
 */
class AgendaFactory extends Factory
{
    protected $model = Agenda::class;

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
            "ingredients" => [
                "alphas" => null,
                "betas" => null,
                "cohumulones" => null,
                "polyphenols" => null,
                "xanthohumol" => null,
                "oils" => null,
                "farnesenes" => null,
                "linalool" => null,
            ],
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
                "betas" => ["min" => 3.0, "max" => 6.0],
                "cohumulones" => ["min" => 20.0, "max" => 30.0],
                "polyphenols" => 4.5,
                "xanthohumol" => null,
                "oils" => ["min" => 1.5, "max" => 3.0],
                "farnesenes" => ["min" => 2.0, "max" => 8.0],
                "linalool" => null,
            ],
            "feeling" => [
                "bitterness" => "high",
                "aromaticity" => "high",
            ],
        ];
    }

    public function definition(): array
    {
        return [
            "user_id" => User::factory(),
            "name" => $this->faker->sentence(3),
            "query" => self::emptyQuery(),
        ];
    }

    public function withQuery(array $query): static
    {
        $base = self::emptyQuery();

        return $this->state(fn(): array => [
            "query" => array_replace_recursive($base, $query),
        ]);
    }

    public function withFullQuery(): static
    {
        return $this->state(fn(): array => [
            "query" => self::fullQuery(),
        ]);
    }
}
