<?php

declare(strict_types=1);

namespace Tests\Feature;

use HopsWeb\Models\Agenda;
use HopsWeb\Models\AgendaResult;
use HopsWeb\Models\User;
use Tests\TestCase;

class LaboratoryDashboardTest extends TestCase
{
    public function testGuestIsRedirectedToLogin(): void
    {
        $response = $this->get(route("laboratory.dashboard"));

        $response->assertRedirect("/login");
    }

    public function testAuthenticatedNonTeamMemberCannotAccess(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route("laboratory.dashboard"));

        $response->assertForbidden();
    }

    public function testTeamMemberCanAccessAndSeeDashboardData(): void
    {
        $member = User::factory()->teamMember()->create();

        $agenda1 = Agenda::factory()
            ->for($member)
            ->withQuery([
                "target" => ["present" => ["Citra"]],
            ])
            ->create(["name" => "Hop Variety A Experiment"]);

        $agenda2 = Agenda::factory()
            ->for($member)
            ->withQuery([
                "target" => ["present" => ["Saaz"]],
            ])
            ->create(["name" => "Hop Variety B Experiment"]);

        AgendaResult::factory()
            ->for($agenda1)
            ->count(2)
            ->create();

        $response = $this->actingAs($member)->get(route("laboratory.dashboard"));

        $response->assertOk();
        $response->assertSee("Hop Variety A Experiment");
        $response->assertSee("Hop Variety B Experiment");
        $response->assertSee("2 runs");

        $response->assertSee("Total Agendas");
        $response->assertSee("2");
        $response->assertSee("Experimental Runs");
        $response->assertSee("2");
        $response->assertSee("Active Researchers");
        $response->assertSee("1");
    }

    public function testTeamMemberCannotSeeOtherUsersAgendas(): void
    {
        $memberA = User::factory()->teamMember()->create();
        $memberB = User::factory()->teamMember()->create();

        $agendaA = Agenda::factory()->for($memberA)->create(["name" => "Member A Agenda"]);
        $agendaB = Agenda::factory()->for($memberB)->create(["name" => "Member B Agenda"]);

        $response = $this->actingAs($memberA)->get(route("laboratory.dashboard"));

        $response->assertOk();
        $response->assertSee("Member A Agenda");
        $response->assertDontSee("Member B Agenda");
    }

    public function testNavigationLinkVisibility(): void
    {
        $response = $this->get(route("hops.index"));
        $response->assertDontSee(route("laboratory.dashboard"));
        $response->assertDontSee("Laboratory");

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route("hops.index"));
        $response->assertDontSee(route("laboratory.dashboard"));
        $response->assertDontSee("Laboratory");

        $member = User::factory()->teamMember()->create();
        $response = $this->actingAs($member)->get(route("hops.index"));
        $response->assertSee(route("laboratory.dashboard"));
        $response->assertSee("Laboratory");
    }

    public function testPaginationWorks(): void
    {
        $member = User::factory()->teamMember()->create();

        Agenda::factory()
            ->for($member)
            ->count(15)
            ->create();

        $response = $this->actingAs($member)->get(route("laboratory.dashboard"));

        $response->assertOk();

        $response->assertSee("Showing");
        $response->assertSee("results");
        $response->assertSee("Next");
    }

    public function testActiveResearchersCountIncreasesWithMoreUsersHavingAgendas(): void
    {
        $member = User::factory()->teamMember()->create();

        // 0 active researchers initially
        $response = $this->actingAs($member)->get(route("laboratory.dashboard"));
        $response->assertSee("Active Researchers");
        $response->assertSee("0");

        // Member A creates an agenda -> 1 active researcher
        Agenda::factory()->for($member)->create();
        $response = $this->actingAs($member)->get(route("laboratory.dashboard"));
        $response->assertSee("1");

        // Member B creates an agenda -> 2 active researchers
        $memberB = User::factory()->teamMember()->create();
        Agenda::factory()->for($memberB)->create();
        $response = $this->actingAs($member)->get(route("laboratory.dashboard"));
        $response->assertSee("2");
    }
}
