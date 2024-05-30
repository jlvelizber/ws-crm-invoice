<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlanControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_get_all_plans(): void
    {
        $response = $this->get('api/plans');

        $response->assertStatus(200);
    }

    public function test_get_only_plan(): void
    {
        $plan = Plan::factory()->create();
        $response = $this->get('api/plans/' . $plan->id);

        $response->assertStatus(200);
    }

    public function test_failure_only_plan(): void
    {
        $response = $this->get('api/plans/999');

        $response->assertStatus(404);
    }


    public function test_insert_new_plan(): void
    {
        // Generate a new plan using the factory
        $planData = Plan::factory()->make()->toArray();

        // Send a POST request to create a new plan
        $response = $this->postJson('/api/plans', $planData);

        // Check if the response status is 201 Created
        $response->assertStatus(201);

        // Assert that the plan has been inserted into the database
        $this->assertDatabaseHas('plans', $planData);
    }

    public function test_fail_for_validate_insert_new_plan(): void
    {
        $planData =  [];
        // Send a POST request to create a new plan
        $response = $this->postJson('/api/plans', $planData);

        // Check if the response status is 422
        $response->assertStatus(422);

        //  // Assert that the plan has been inserted into the database
        //  $this->assertDatabaseHas('plans', $planData);
    }


    
    public function test_update_a_plan(): void
    {
        // Generate a new plan using the factory
        $planData = Plan::factory()->make()->toArray();
        $planDataUpdate = Plan::factory()->make()->toArray();

        // Send a POST request to create a new plan
        $this->postJson('/api/plans', $planData);

        $planInserted = Plan::first();

         // Send a PUT request to update a plan
         $response = $this->putJson('/api/plans/' . $planInserted->id, $planDataUpdate);

        // Check if the response status is 201 Created
        $response->assertStatus(200);

        // Assert that the plan has been inserted into the database
        $this->assertDatabaseHas('plans', $planDataUpdate);
    }



    public function test_fail_for_validate_update_plan(): void
    {
        // Generate a new plan using the factory
        $planData = Plan::factory(
            [
                'short_name' => 'unique'
            ]
        )->make()->toArray();

        $this->postJson('/api/plans', $planData);

        $planInserted = Plan::first();
        $updateData = [];

        // Send a PUT request to update a plan
        $response = $this->putJson('/api/plans/' . $planInserted->id, $updateData);

        // Check if the response status is 422

        $response->assertStatus(422);

      
    }

    public function test_delete_plan()
    {
        $planData = Plan::factory()->make()->toArray();
        $this->postJson('/api/plans', $planData);
        $planInserted = Plan::first();

        // Send a PUT request to update a plan
        $response = $this->deleteJson('/api/plans/' . $planInserted->id);

        // Check if the response status is 200

        $response->assertStatus(200);


    }
}
