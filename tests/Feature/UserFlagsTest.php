<?php

declare(strict_types=1);

namespace Tests\Feature;

use HopsWeb\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class UserFlagsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware(["web", "auth", "is_admin"])->get("/test-admin", fn() => "admin-ok");
        Route::middleware(["web", "auth", "is_team_member"])->get("/test-team", fn() => "team-ok");
    }

    public function testNewUserHasDefaultFlags(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->is_admin);
        $this->assertFalse($user->is_team_member);
    }

    public function testAdminFactoryStateSetsIsAdmin(): void
    {
        $user = User::factory()->admin()->create();

        $this->assertTrue($user->is_admin);
        $this->assertFalse($user->is_team_member);
    }

    public function testTeamMemberFactoryStateSetsIsTeamMember(): void
    {
        $user = User::factory()->teamMember()->create();

        $this->assertFalse($user->is_admin);
        $this->assertTrue($user->is_team_member);
    }

    public function testAdminMiddlewareAllowsAdmin(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get("/test-admin");

        $response->assertOk();
        $response->assertSee("admin-ok");
    }

    public function testAdminMiddlewareBlocksNonAdmin(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get("/test-admin");

        $response->assertForbidden();
    }

    public function testTeamMemberMiddlewareAllowsTeamMember(): void
    {
        $member = User::factory()->teamMember()->create();

        $response = $this->actingAs($member)->get("/test-team");

        $response->assertOk();
        $response->assertSee("team-ok");
    }

    public function testTeamMemberMiddlewareBlocksNonTeamMember(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get("/test-team");

        $response->assertForbidden();
    }

    public function testUnauthenticatedUserIsRedirected(): void
    {
        $response = $this->get("/test-admin");

        $response->assertRedirect("/login");
    }
}
