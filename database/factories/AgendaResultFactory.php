<?php

declare(strict_types=1);

namespace Database\Factories;

use HopsWeb\Models\Agenda;
use HopsWeb\Models\AgendaResult;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AgendaResult>
 */
class AgendaResultFactory extends Factory
{
    protected $model = AgendaResult::class;

    public function definition(): array
    {
        return [
            "agenda_id" => Agenda::factory(),
            "parameters" => [],
            "response" => null,
        ];
    }

    public function withParameters(array $parameters): static
    {
        return $this->state(fn(): array => [
            "parameters" => $parameters,
        ]);
    }

    public function withResponse(array $response): static
    {
        return $this->state(fn(): array => [
            "response" => $response,
        ]);
    }
}
