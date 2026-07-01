<?php

declare(strict_types=1);

namespace HopsWeb\Actions;

use HopsWeb\DTO\Engine\ComparisonQueryDTO;
use HopsWeb\DTO\Engine\HopResultDTO;
use HopsWeb\Http\Requests\CreateAgendaRunRequest;
use HopsWeb\Models\Agenda;
use HopsWeb\Models\AgendaResult;
use HopsWeb\Models\Hop;
use HopsWeb\Services\ComparisonService;

class CreateAgendaRunAction
{
    public function __construct(
        private readonly ComparisonService $comparisonService,
    ) {}

    public function execute(Agenda $agenda, CreateAgendaRunRequest $request): AgendaResult
    {
        $data = $request->getData();

        $queryData = $agenda->query;
        $queryData["weights"] = $data["module_weights"];
        $queryData["biochemical_weights"] = $data["biochemical_weights"];

        $dto = ComparisonQueryDTO::fromArray($queryData);
        $responseDTO = $this->comparisonService->compare($dto);

        $slugs = collect($responseDTO->results)->pluck("hopId")->filter()->toArray();
        $hopsByName = Hop::query()->whereIn("slug", $slugs)->pluck("name", "slug")->toArray();

        $resultsArray = array_map(fn(HopResultDTO $result): array => [
            "name" => $hopsByName[$result->hopId] ?? ucfirst($result->hopId),
            "score" => $result->similarityScore,
        ], $responseDTO->results);

        return AgendaResult::query()->create([
            "agenda_id" => $agenda->id,
            "parameters" => [
                "weights" => $data["module_weights"],
                "module_weights" => $data["module_weights"],
                "biochemical_weights" => $data["biochemical_weights"],
            ],
            "response" => $resultsArray,
        ]);
    }
}
