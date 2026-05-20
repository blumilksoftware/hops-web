<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers;

use HopsWeb\DTO\Engine\ComparisonQueryDTO;
use HopsWeb\Http\Requests\ComparisonQueryRequest;
use HopsWeb\Models\Hop;
use HopsWeb\Models\HopQuery;
use HopsWeb\Services\ComparisonEngine\NLPResolverClientInterface;
use HopsWeb\Services\ComparisonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ComparisonController extends Controller
{
    public function __construct(
        private readonly ComparisonService $comparisonService,
        private readonly NLPResolverClientInterface $nlpResolver,
    ) {}

    public function index(Request $request): View
    {
        $user = Auth::user();
        $historyCollection = $user->hopQueries()->latest()->take(15)->get();

        $activeQuery = null;
        $hops = collect();
        $results = [];

        if ($request->filled("history_id")) {
            $activeQuery = $user->hopQueries()->find($request->input("history_id"));

            if ($activeQuery) {
                $results = $activeQuery->response["results"] ?? [];

                $slugs = collect($results)->pluck("hop_id")->filter()->toArray();

                if (!empty($slugs)) {
                    $hops = Hop::whereIn("slug", $slugs)->get()->keyBy("slug");
                }
            }
        }

        $history = $historyCollection->map(fn(HopQuery $q): array => [
            "id" => $q->id,
            "isActive" => $activeQuery && $activeQuery->id === $q->id,
            "isNlp" => isset($q->query["_nlp_query"]),
            "nlpQuery" => $q->query["_nlp_query"] ?? null,
            "created_at" => $q->created_at,
            "aromaCount" => isset($q->query["aroma"]["present"]) && is_array($q->query["aroma"]["present"]) ? count($q->query["aroma"]["present"]) : 0,
            "firstTarget" => isset($q->query["target"]["present"]) && is_array($q->query["target"]["present"]) && !empty($q->query["target"]["present"]) ? $q->query["target"]["present"][0] : null,
            "hasIngredients" => isset($q->query["ingredients"]) && !empty($q->query["ingredients"]),
        ]);

        $mappedResults = collect($results)->map(function (array $result) use ($hops): array {
            $slug = $result["hop_id"] ?? null;
            $hop = $slug ? ($hops[$slug] ?? null) : null;

            return [
                "slug" => $slug,
                "score" => $result["similarity_score"] ?? 0.0,
                "explainability" => $result["explainability"] ?? [],
                "hop" => $hop,
                "activeAromas" => $hop ? collect($hop->getActiveAromas())->map(fn($a) => $a->label())->toArray() : [],
            ];
        })->toArray();

        return view("comparison.index", [
            "history" => $history,
            "activeQuery" => $activeQuery,
            "results" => $mappedResults,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $type = $request->input("type", "nlp");

        if ($type === "nlp") {
            $nlpQuery = $request->input("natural_language_query");

            if (empty($nlpQuery)) {
                return back()->withErrors(["natural_language_query" => "The query field cannot be empty."])->withInput();
            }

            $dto = $this->nlpResolver->resolve($nlpQuery);
            $queryData = $dto->toArray();

            $queryData["_nlp_query"] = $nlpQuery;
        } else {
            if ($request->filled("query_json")) {
                $queryData = json_decode($request->input("query_json"), true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    return back()->withErrors(["query_json" => "Invalid JSON format: " . json_last_error_msg()])->withInput();
                }
            } else {
                $queryData = $request->only(["target", "aroma", "description", "ingredients", "feeling"]);
            }

            $queryData = array_filter($queryData ?? [], fn($value): bool => $value !== null);

            $validationRequest = new ComparisonQueryRequest();
            $validator = Validator::make(
                $queryData,
                $validationRequest->rules(),
                $validationRequest->messages(),
                $validationRequest->attributes(),
            );
            $validationRequest->withValidator($validator);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $dto = ComparisonQueryDTO::fromArray($queryData);
        }

        $responseDTO = $this->comparisonService->compare($dto);

        $resultsArray = array_map(fn($result): array => [
            "hop_id" => $result->hopId,
            "similarity_score" => $result->similarityScore,
            "explainability" => $result->explainability,
        ], $responseDTO->results);

        $hopQuery = HopQuery::create([
            "user_id" => $user->id,
            "query" => $queryData,
            "response" => [
                "results" => $resultsArray,
                "metadata" => [
                    "modules_used" => $responseDTO->metadata->modulesUsed,
                    "execution_time_ms" => $responseDTO->metadata->executionTimeMs,
                ],
            ],
        ]);

        return redirect()->route("comparison.index", ["history_id" => $hopQuery->id])
            ->with("success", "Comparison executed successfully.");
    }
}
