<?php

declare(strict_types=1);

namespace Tests\Feature\Agenda;

use HopsWeb\Models\Agenda;
use HopsWeb\Models\AgendaResult;
use HopsWeb\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompareAgendaRunsTest extends TestCase
{
    use RefreshDatabase;

    protected User $member;
    protected Agenda $agenda;
    protected AgendaResult $run1;
    protected AgendaResult $run2;
    protected array $moduleWeights1 = [
        "aroma" => 0.40,
        "biochemical" => 0.30,
        "description" => 0.20,
        "feeling" => 0.10,
    ];
    protected array $moduleWeights2 = [
        "aroma" => 0.50,
        "biochemical" => 0.20,
        "description" => 0.20,
        "feeling" => 0.10,
    ];
    protected array $biochemicalWeights = [
        "alpha_acid" => 0.20,
        "beta_acid" => 0.15,
        "cohumulone" => 0.15,
        "total_oil" => 0.15,
        "polyphenol" => 0.10,
        "xanthohumol" => 0.10,
        "farnesene" => 0.10,
        "linalool" => 0.05,
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->member = User::factory()->teamMember()->create();
        $this->agenda = Agenda::factory()->for($this->member)->create();

        $this->run1 = AgendaResult::query()->create([
            "agenda_id" => $this->agenda->id,
            "parameters" => [
                "weights" => $this->moduleWeights1,
                "biochemical_weights" => $this->biochemicalWeights,
            ],
            "response" => [
                ["name" => "Cascade", "score" => 0.95],
                ["name" => "Centennial", "score" => 0.88],
            ],
        ]);

        $this->run2 = AgendaResult::query()->create([
            "agenda_id" => $this->agenda->id,
            "parameters" => [
                "weights" => $this->moduleWeights2,
                "biochemical_weights" => $this->biochemicalWeights,
            ],
            "response" => [
                ["name" => "Cascade", "score" => 0.92],
                ["name" => "Centennial", "score" => 0.90],
            ],
        ]);
    }

    public function testGuestIsRedirectedToLoginOnCompare(): void
    {
        $this->get(route("laboratory.agendas.compare", [
            "agenda" => $this->agenda,
            "run_ids" => [$this->run1->id, $this->run2->id],
        ]))
            ->assertRedirect("/login");
    }

    public function testNonTeamMemberCannotAccessCompare(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route("laboratory.agendas.compare", [
                "agenda" => $this->agenda,
                "run_ids" => [$this->run1->id, $this->run2->id],
            ]))
            ->assertForbidden();
    }

    public function testTeamMemberCannotCompareAnotherUsersAgendaRuns(): void
    {
        $otherMember = User::factory()->teamMember()->create();

        $this->actingAs($otherMember)
            ->get(route("laboratory.agendas.compare", [
                "agenda" => $this->agenda,
                "run_ids" => [$this->run1->id, $this->run2->id],
            ]))
            ->assertForbidden();
    }

    public function testComparingFewerThanTwoRunsRedirectsBack(): void
    {
        $this->actingAs($this->member)
            ->get(route("laboratory.agendas.compare", [
                "agenda" => $this->agenda,
                "run_ids" => [$this->run1->id],
            ]))
            ->assertRedirect(route("laboratory.index"))
            ->assertSessionHas("error", __("Please select at least 2 runs to compare."));
    }

    public function testTeamMemberCanCompareOwnRuns(): void
    {
        $this->actingAs($this->member)
            ->get(route("laboratory.agendas.compare", [
                "agenda" => $this->agenda,
                "run_ids" => [$this->run1->id, $this->run2->id],
            ]))
            ->assertOk()
            ->assertSee(__("Compare Runs Side-by-Side"))
            ->assertSee("Run #1")
            ->assertSee("Run #2")
            ->assertSee("40%")
            ->assertSee("50%")
            ->assertSee("Cascade")
            ->assertSee("Centennial");
    }
}
