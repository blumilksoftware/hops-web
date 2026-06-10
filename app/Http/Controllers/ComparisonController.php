<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers;

use HopsWeb\DTO\Engine\ComparisonQueryDTO;
use HopsWeb\Enums\AromaProfile;
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

        $activeQuery = null;

        if ($request->filled("history_id")) {
            $activeQuery = $user->hopQueries()->find($request->input("history_id"));
        }

        $historyPaginator = $user->hopQueries()->latest()->paginate(10)->withQueryString();

        $history = $historyPaginator->through(fn(HopQuery $query): array => [
            "id" => $query->id,
            "isActive" => $activeQuery && $activeQuery->id === $query->id,
            "isNlp" => isset($query->query["_nlp_query"]),
            "nlpQuery" => $query->query["_nlp_query"] ?? null,
            "created_at" => $query->created_at,
            "aromaCount" => isset($query->query["aroma"]["present"]) && is_array($query->query["aroma"]["present"]) ? count($query->query["aroma"]["present"]) : 0,
            "firstTarget" => isset($query->query["target"]["present"]) && is_array($query->query["target"]["present"]) && !empty($query->query["target"]["present"]) ? $query->query["target"]["present"][0] : null,
            "hasIngredients" => isset($query->query["ingredients"]) && !empty($query->query["ingredients"]),
        ]);

        $hops = collect();
        $results = [];

        if ($activeQuery) {
            $results = $activeQuery->response["results"] ?? [];

            $slugs = collect($results)->pluck("hop_id")->filter()->toArray();

            if (!empty($slugs)) {
                $hops = Hop::whereIn("slug", $slugs)->get()->keyBy("slug");
            }
        }

        $mappedResults = collect($results)->map(function (array $result) use ($hops): array {
            $slug = $result["hop_id"] ?? null;
            $hop = $slug ? ($hops[$slug] ?? null) : null;

            return [
                "slug" => $slug,
                "score" => $result["similarity_score"] ?? 0.0,
                "explainability" => $result["explainability"] ?? [],
                "hop" => $hop,
                "activeAromas" => $hop ? collect($hop->getActiveAromas())->map(fn(AromaProfile $aroma): string => $aroma->label())->toArray() : [],
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
