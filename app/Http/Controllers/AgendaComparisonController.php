<?php

declare(strict_types=1);

namespace HopsWeb\Http\Controllers;

use HopsWeb\Models\Agenda;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgendaComparisonController extends Controller
{
    use AuthorizesRequests;

    public function show(Request $request, Agenda $agenda): View|RedirectResponse
    {
        $this->authorize("compare", $agenda);

        $runIds = $request->input("run_ids", []);

        if (!is_array($runIds) || count($runIds) < 2) {
            return redirect()->route("laboratory.index")
                ->with("error", __("Please select at least 2 runs to compare."));
        }

        $runs = $agenda->results()
            ->whereIn("id", $runIds)
            ->get();

        if ($runs->count() < 2) {
            return redirect()->route("laboratory.index")
                ->with("error", __("Some selected runs could not be found."));
        }

        $moduleWeights = [
            "aroma" => __("Aroma"),
            "biochemical" => __("Biochemical"),
            "description" => __("Description"),
            "feeling" => __("Feeling"),
        ];

        $biochemicalWeights = [
            "alpha_acid" => __("Alpha Acid"),
            "beta_acid" => __("Beta Acid"),
            "cohumulone" => __("Cohumulone"),
            "total_oil" => __("Total Oil"),
            "polyphenol" => __("Polyphenols"),
            "xanthohumol" => __("Xanthohumol"),
            "farnesene" => __("Farnesene"),
            "linalool" => __("Linalool"),
        ];

        $moduleRows = [];

        foreach ($moduleWeights as $key => $label) {
            $rowValues = [];

            foreach ($runs as $run) {
                $rowValues[$run->id] = $run->parameters["weights"][$key] ?? 0.0;
            }
            $isDifferent = count(array_unique(array_map("strval", $rowValues))) > 1;
            $moduleRows[$key] = [
                "label" => $label,
                "values" => $rowValues,
                "isDifferent" => $isDifferent,
            ];
        }

        $biochemicalRows = [];

        foreach ($biochemicalWeights as $key => $label) {
            $rowValues = [];

            foreach ($runs as $run) {
                $rowValues[$run->id] = $run->parameters["biochemical_weights"][$key] ?? 0.0;
            }
            $isDifferent = count(array_unique(array_map("strval", $rowValues))) > 1;
            $biochemicalRows[$key] = [
                "label" => $label,
                "values" => $rowValues,
                "isDifferent" => $isDifferent,
            ];
        }

        $hopNames = [];

        foreach ($runs as $run) {
            if (!empty($run->response)) {
                foreach ($run->response as $match) {
                    if (isset($match["name"])) {
                        $hopNames[] = $match["name"];
                    }
                }
            }
        }
        $hopNames = array_unique($hopNames);
        sort($hopNames);

        $scoreRows = [];

        foreach ($hopNames as $hopName) {
            $rowValues = [];

            foreach ($runs as $run) {
                $score = null;

                if (!empty($run->response)) {
                    foreach ($run->response as $match) {
                        if (($match["name"] ?? null) === $hopName) {
                            $score = $match["score"] ?? 0.0;

                            break;
                        }
                    }
                }
                $rowValues[$run->id] = $score;
            }
            $nonNullValues = array_map(fn($v) => $v ?? 0.0, $rowValues);
            $isDifferent = count(array_unique(array_map("strval", $nonNullValues))) > 1;
            $maxScore = count($nonNullValues) > 0 ? max($nonNullValues) : 0.0;

            $scoreRows[] = [
                "label" => $hopName,
                "values" => $rowValues,
                "isDifferent" => $isDifferent,
                "maxScore" => $maxScore,
            ];
        }

        return view("laboratory.agenda.compare", [
            "agenda" => $agenda,
            "runs" => $runs,
            "moduleRows" => $moduleRows,
            "biochemicalRows" => $biochemicalRows,
            "scoreRows" => $scoreRows,
        ]);
    }
}
