<?php

declare(strict_types=1);

namespace HopsWeb\DTO\Engine;

readonly class ComparisonQueryDTO
{
    /**
     * @param array{present?: array<string>, absent?: array<string>}|null $target
     * @param array{present?: array<string>, absent?: array<string>}|null $aroma
     * @param array{present?: array<string>, absent?: array<string>}|null $description
     * @param array<string, mixed>|null $ingredients
     * @param array{bitterness?: string, aromaticity?: string}|null $feeling
     */
    public function __construct(
        public ?array $target = null,
        public ?array $aroma = null,
        public ?array $description = null,
        public ?array $ingredients = null,
        public ?array $feeling = null,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            target: $data["target"] ?? null,
            aroma: $data["aroma"] ?? null,
            description: $data["description"] ?? null,
            ingredients: $data["ingredients"] ?? null,
            feeling: $data["feeling"] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            "target" => $this->target,
            "aroma" => $this->aroma,
            "description" => $this->description,
            "ingredients" => $this->ingredients,
            "feeling" => $this->feeling,
        ], fn($value): bool => $value !== null);
    }
}
