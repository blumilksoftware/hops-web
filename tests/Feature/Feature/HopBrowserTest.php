<?php

declare(strict_types=1);

namespace Tests\Feature\Feature;

use Tests\TestCase;

class HopBrowserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testExample(): void
    {
        $response = $this->get("/");

        $response->assertStatus(200);
    }
}
