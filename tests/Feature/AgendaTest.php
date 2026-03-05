<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Factories\AgendaFactory;
use HopsWeb\Models\Agenda;
use HopsWeb\Models\AgendaResult;
use HopsWeb\Models\User;
use Tests\TestCase;

class AgendaTest extends TestCase
{
    public function testAgendaCanBeCreatedWithBaseQuery(): void
    {
        $user = User::factory()->teamMember()->create();
        $fullQuery = AgendaFactory::fullQuery();

        $agenda = Agenda::factory()
            ->for($user)
            ->withFullQuery()
            ->create(["name" => "Alpha tuning session"]);

        $this->assertDatabaseHas("agendas", [
            "id" => $agenda->id,
            "user_id" => $user->id,
            "name" => "Alpha tuning session",
        ]);

        $freshAgenda = $agenda->fresh();

        $this->assertIsArray($freshAgenda->query);
        $this->assertEquals($fullQuery, $freshAgenda->query);
    }

    public function testAgendaCanBeCreatedWithEmptyQuery(): void
    {
        $emptyQuery = AgendaFactory::emptyQuery();

        $agenda = Agenda::factory()->create();

        $freshAgenda = $agenda->fresh();

        $this->assertIsArray($freshAgenda->query);
        $this->assertEquals($emptyQuery, $freshAgenda->query);
    }

    public function testAgendaCanBeCreatedWithPartialQuery(): void
    {
        $agenda = Agenda::factory()
            ->withQuery([
                "target" => [
                    "present" => ["Citra"],
                ],
                "feeling" => [
                    "bitterness" => "medium",
                ],
            ])
            ->create();

        $freshAgenda = $agenda->fresh();

        $this->assertEquals(["Citra"], $freshAgenda->query["target"]["present"]);
        $this->assertEmpty($freshAgenda->query["target"]["absent"]);
        $this->assertEquals("medium", $freshAgenda->query["feeling"]["bitterness"]);
        $this->assertNull($freshAgenda->query["feeling"]["aromaticity"]);
    }

    public function testAgendaBelongsToUser(): void
    {
        $user = User::factory()->teamMember()->create();

        $agenda = Agenda::factory()
            ->for($user)
            ->create();

        $this->assertInstanceOf(User::class, $agenda->user);
        $this->assertEquals($user->id, $agenda->user->id);
    }

    public function testAgendaResultCanBeCreatedWithParametersAndResponse(): void
    {
        $agenda = Agenda::factory()->create();

        $parameters = [
            "weights" => [
                "aroma" => 0.4,
                "biochemical" => 0.3,
                "description" => 0.2,
                "feeling" => 0.1,
            ],
        ];

        $response = [
            ["id" => 1, "name" => "Citra", "score" => 0.95],
            ["id" => 2, "name" => "Mosaic", "score" => 0.85],
        ];

        $result = AgendaResult::factory()
            ->for($agenda)
            ->withParameters($parameters)
            ->withResponse($response)
            ->create();

        $freshResult = $result->fresh();

        $this->assertIsArray($freshResult->parameters);
        $this->assertEquals(0.4, $freshResult->parameters["weights"]["aroma"]);
        $this->assertIsArray($freshResult->response);
        $this->assertCount(2, $freshResult->response);
        $this->assertEquals("Citra", $freshResult->response[0]["name"]);
        $this->assertEquals(0.95, $freshResult->response[0]["score"]);
    }

    public function testAgendaResultBelongsToAgenda(): void
    {
        $agenda = Agenda::factory()->create();

        $result = AgendaResult::factory()
            ->for($agenda)
            ->create();

        $this->assertInstanceOf(Agenda::class, $result->agenda);
        $this->assertEquals($agenda->id, $result->agenda->id);
    }

    public function testAgendaHasManyResults(): void
    {
        $agenda = Agenda::factory()->create();

        AgendaResult::factory()
            ->for($agenda)
            ->count(3)
            ->create();

        $this->assertCount(3, $agenda->results);
    }

    public function testUserHasManyAgendas(): void
    {
        $user = User::factory()->teamMember()->create();

        Agenda::factory()
            ->for($user)
            ->count(3)
            ->create();

        $this->assertCount(3, $user->agendas);
    }

    public function testAgendaIsDeletedWhenUserIsDeleted(): void
    {
        $user = User::factory()->teamMember()->create();

        $agenda = Agenda::factory()
            ->for($user)
            ->create();

        $agendaId = $agenda->id;

        $user->delete();

        $this->assertDatabaseMissing("agendas", ["id" => $agendaId]);
    }

    public function testAgendaResultIsDeletedWhenAgendaIsDeleted(): void
    {
        $agenda = Agenda::factory()->create();

        $result = AgendaResult::factory()
            ->for($agenda)
            ->create();

        $resultId = $result->id;

        $agenda->delete();

        $this->assertDatabaseMissing("agenda_results", ["id" => $resultId]);
    }

    public function testAgendaResultIsDeletedWhenUserIsDeleted(): void
    {
        $user = User::factory()->teamMember()->create();

        $agenda = Agenda::factory()
            ->for($user)
            ->create();

        $result = AgendaResult::factory()
            ->for($agenda)
            ->create();

        $resultId = $result->id;

        $user->delete();

        $this->assertDatabaseMissing("agenda_results", ["id" => $resultId]);
    }
}
