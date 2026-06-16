<?php

declare(strict_types=1);

namespace Tests\Feature\Agenda;

use HopsWeb\Models\Agenda;
use HopsWeb\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateAgendaRunTest extends TestCase
{
    use RefreshDatabase;

    protected User $member;
    protected Agenda $agenda;
    protected array $validModuleWeights = [
        "aroma" => "0.40",
        "biochemical" => "0.30",
        "description" => "0.20",
        "feeling" => "0.10",
    ];
    protected array $validBiochemicalWeights = [
        "alpha_acid" => "0.20",
        "beta_acid" => "0.15",
        "cohumulone" => "0.15",
        "total_oil" => "0.15",
        "polyphenol" => "0.10",
        "xanthohumol" => "0.10",
        "farnesene" => "0.10",
        "linalool" => "0.05",
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->member = User::factory()->teamMember()->create();
        $this->agenda = Agenda::factory()->for($this->member)->create();
    }

    public function testGuestIsRedirectedToLoginOnCreateForm(): void
    {
        $this->get(route("laboratory.agendas.runs.create", $this->agenda))
            ->assertRedirect("/login");
    }

    public function testGuestIsRedirectedToLoginOnStore(): void
    {
        $this->post(route("laboratory.agendas.runs.store", $this->agenda), [])
            ->assertRedirect("/login");
    }

    public function testNonTeamMemberCannotAccessCreateForm(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route("laboratory.agendas.runs.create", $this->agenda))
            ->assertForbidden();
    }

    public function testTeamMemberCannotAccessAnotherUsersAgendaRunForm(): void
    {
        $otherMember = User::factory()->teamMember()->create();

        $this->actingAs($otherMember)
            ->get(route("laboratory.agendas.runs.create", $this->agenda))
            ->assertForbidden();
    }

    public function testTeamMemberCannotStoreRunForAnotherUsersAgenda(): void
    {
        $otherMember = User::factory()->teamMember()->create();

        $this->actingAs($otherMember)
            ->post(route("laboratory.agendas.runs.store", $this->agenda), [
                "module_weights" => $this->validModuleWeights,
                "biochemical_weights" => $this->validBiochemicalWeights,
            ])
            ->assertForbidden();
    }

    public function testTeamMemberCanSeeCreateForm(): void
    {
        $this->actingAs($this->member)
            ->get(route("laboratory.agendas.runs.create", $this->agenda))
            ->assertOk()
            ->assertSee(__("Configure Parameter Set"))
            ->assertSee(__("Module Weights"))
            ->assertSee(__("Biochemical Weights"))
            ->assertSee(__("Generated Parameter Set JSON"));
    }

    public function testTeamMemberCanSaveValidParameterSet(): void
    {
        $this->actingAs($this->member)
            ->post(route("laboratory.agendas.runs.store", $this->agenda), [
                "module_weights" => $this->validModuleWeights,
                "biochemical_weights" => $this->validBiochemicalWeights,
            ])
            ->assertRedirect(route("laboratory.index"))
            ->assertSessionHas("success");

        $this->assertDatabaseHas("agenda_results", [
            "agenda_id" => $this->agenda->id,
        ]);

        $run = $this->agenda->results()->first();

        $this->assertEqualsWithDelta(0.40, $run->parameters["module_weights"]["aroma"], 0.001);
        $this->assertEqualsWithDelta(0.30, $run->parameters["module_weights"]["biochemical"], 0.001);
        $this->assertEqualsWithDelta(0.20, $run->parameters["module_weights"]["description"], 0.001);
        $this->assertEqualsWithDelta(0.10, $run->parameters["module_weights"]["feeling"], 0.001);
        $this->assertEqualsWithDelta(0.20, $run->parameters["biochemical_weights"]["alpha_acid"], 0.001);
        $this->assertEqualsWithDelta(0.05, $run->parameters["biochemical_weights"]["linalool"], 0.001);
    }

    public function testModuleWeightsNotSummingToOneAreRejected(): void
    {
        $this->actingAs($this->member)
            ->post(route("laboratory.agendas.runs.store", $this->agenda), [
                "module_weights" => [
                    "aroma" => "0.50",
                    "biochemical" => "0.50",
                    "description" => "0.50",
                    "feeling" => "0.50",
                ],
                "biochemical_weights" => $this->validBiochemicalWeights,
            ])
            ->assertSessionHasErrors(["module_weights"]);

        $this->assertDatabaseCount("agenda_results", 0);
    }

    public function testBiochemicalWeightsNotSummingToOneAreRejected(): void
    {
        $this->actingAs($this->member)
            ->post(route("laboratory.agendas.runs.store", $this->agenda), [
                "module_weights" => $this->validModuleWeights,
                "biochemical_weights" => [
                    "alpha_acid" => "0.50",
                    "beta_acid" => "0.50",
                    "cohumulone" => "0.50",
                    "total_oil" => "0.50",
                    "polyphenol" => "0.50",
                    "xanthohumol" => "0.50",
                    "farnesene" => "0.50",
                    "linalool" => "0.50",
                ],
            ])
            ->assertSessionHasErrors(["biochemical_weights"]);

        $this->assertDatabaseCount("agenda_results", 0);
    }

    public function testWeightOutOfRangeIsRejected(): void
    {
        $this->actingAs($this->member)
            ->post(route("laboratory.agendas.runs.store", $this->agenda), [
                "module_weights" => array_merge($this->validModuleWeights, ["aroma" => "1.5"]),
                "biochemical_weights" => $this->validBiochemicalWeights,
            ])
            ->assertSessionHasErrors(["module_weights.aroma"]);

        $this->assertDatabaseCount("agenda_results", 0);
    }

    public function testNegativeWeightIsRejected(): void
    {
        $this->actingAs($this->member)
            ->post(route("laboratory.agendas.runs.store", $this->agenda), [
                "module_weights" => array_merge($this->validModuleWeights, ["feeling" => "-0.1"]),
                "biochemical_weights" => $this->validBiochemicalWeights,
            ])
            ->assertSessionHasErrors(["module_weights.feeling"]);

        $this->assertDatabaseCount("agenda_results", 0);
    }

    public function testMissingModuleWeightsAreRejected(): void
    {
        $this->actingAs($this->member)
            ->post(route("laboratory.agendas.runs.store", $this->agenda), [
                "biochemical_weights" => $this->validBiochemicalWeights,
            ])
            ->assertSessionHasErrors(["module_weights"]);

        $this->assertDatabaseCount("agenda_results", 0);
    }
}
