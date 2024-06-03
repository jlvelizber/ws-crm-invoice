<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WPApiControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_check_health(): void
    {
        $response = $this->getJson('/api/health-check');

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_check_health_message(): void
    {
        $response = $this->getJson('/api/health-check');
        $response->assertJson(['status' => 'ok']);
    }
}
