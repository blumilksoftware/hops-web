<?php

declare(strict_types=1);

namespace HopsWeb\DTO\Engine;

readonly class ComparisonQueryDTO
{
    public function __construct(
        public ?array $target = null,
        public ?array $aroma = null,
        public ?array $description = null,
        public ?array $ingredients = null,
        public ?array $feeling = null,
        public ?array $weights = null,
        public ?array $biochemicalWeights = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            target: $data["target"] ?? null,
            aroma: $data["aroma"] ?? null,
            description: $data["description"] ?? null,
            ingredients: $data["ingredients"] ?? null,
            feeling: $data["feeling"] ?? null,
            weights: $data["weights"] ?? null,
            biochemicalWeights: $data["biochemical_weights"] ?? $data["biochemicalWeights"] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            "target" => $this->target,
            "aroma" => $this->aroma,
            "description" => $this->description,
            "ingredients" => $this->ingredients,
            "feeling" => $this->feeling,
            "weights" => $this->weights,
            "biochemical_weights" => $this->biochemicalWeights,
        ], fn($value): bool => $value !== null);
    }
}
