<?php

declare(strict_types=1);

namespace Tests\Feature\Agenda;

use HopsWeb\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateAgendaTest extends TestCase
{
    use RefreshDatabase;

    protected User $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->member = User::factory()->teamMember()->create();
    }

    public function testGuestIsRedirectedToLoginOnCreateForm(): void
    {
        $this->get(route("laboratory.agendas.create"))
            ->assertRedirect("/login");
    }

    public function testGuestIsRedirectedToLoginOnStore(): void
    {
        $this->post(route("laboratory.agendas.store"), ["name" => "Test"])
            ->assertRedirect("/login");
    }

    public function testNonTeamMemberCannotAccessCreateForm(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route("laboratory.agendas.create"))
            ->assertForbidden();
    }

    public function testNonTeamMemberCannotStoreAgenda(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route("laboratory.agendas.store"), ["name" => "Test"])
            ->assertForbidden();
    }

    public function testTeamMemberCanSeeCreateForm(): void
    {
        $this->actingAs($this->member)
            ->get(route("laboratory.agendas.create"))
            ->assertOk()
            ->assertSee(__("Create New Agenda"))
            ->assertSee(__("Agenda Name"))
            ->assertSee(__("Base Query (JSON, optional)"));
    }

    public function testTeamMemberCanCreateAgendaWithoutQuery(): void
    {
        $this->actingAs($this->member)
            ->post(route("laboratory.agendas.store"), [
                "name" => "Alpha Tuning Session",
            ])
            ->assertRedirect(route("laboratory.index"))
            ->assertSessionHas("success");

        $this->assertDatabaseHas("agendas", [
            "user_id" => $this->member->id,
            "name" => "Alpha Tuning Session",
        ]);
    }

    public function testTeamMemberCanCreateAgendaWithBaseQuery(): void
    {
        $query = [
            "target" => ["present" => ["Citra"], "absent" => []],
            "aroma" => ["present" => ["citrusy"], "absent" => []],
        ];

        $this->actingAs($this->member)
            ->post(route("laboratory.agendas.store"), [
                "name" => "Citra Experiment",
                "query_json" => json_encode($query),
            ])
            ->assertRedirect(route("laboratory.index"))
            ->assertSessionHas("success");

        $this->assertDatabaseHas("agendas", [
            "user_id" => $this->member->id,
            "name" => "Citra Experiment",
        ]);

        $agenda = $this->member->agendas()->firstWhere("name", "Citra Experiment");

        $this->assertIsArray($agenda->query);
        $this->assertEquals(["Citra"], $agenda->query["target"]["present"]);
        $this->assertEquals(["citrusy"], $agenda->query["aroma"]["present"]);
    }

    public function testAgendaIsAssignedToAuthenticatedUser(): void
    {
        $otherMember = User::factory()->teamMember()->create();

        $this->actingAs($this->member)
            ->post(route("laboratory.agendas.store"), [
                "name" => "My Agenda",
            ]);

        $this->assertDatabaseHas("agendas", [
            "user_id" => $this->member->id,
            "name" => "My Agenda",
        ]);

        $this->assertDatabaseMissing("agendas", [
            "user_id" => $otherMember->id,
            "name" => "My Agenda",
        ]);
    }

    public function testNameIsRequired(): void
    {
        $this->actingAs($this->member)
            ->post(route("laboratory.agendas.store"), [])
            ->assertSessionHasErrors(["name"]);

        $this->assertDatabaseCount("agendas", 0);
    }

    public function testNameCannotExceed255Characters(): void
    {
        $this->actingAs($this->member)
            ->post(route("laboratory.agendas.store"), [
                "name" => str_repeat("a", 256),
            ])
            ->assertSessionHasErrors(["name"]);

        $this->assertDatabaseCount("agendas", 0);
    }

    public function testInvalidJsonQueryIsRejected(): void
    {
        $this->actingAs($this->member)
            ->post(route("laboratory.agendas.store"), [
                "name" => "Test Agenda",
                "query_json" => "not valid json {{{",
            ])
            ->assertSessionHasErrors(["query_json"]);

        $this->assertDatabaseCount("agendas", 0);
    }

    public function testEmptyQueryJsonIsAccepted(): void
    {
        $this->actingAs($this->member)
            ->post(route("laboratory.agendas.store"), [
                "name" => "Test Agenda",
                "query_json" => "",
            ])
            ->assertRedirect(route("laboratory.index"));

        $this->assertDatabaseHas("agendas", ["name" => "Test Agenda"]);
    }
}
