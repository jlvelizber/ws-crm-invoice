<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_get_all_customers(): void
    {
        $response = $this->get('api/customers');

        $response->assertStatus(200);
    }

    public function test_get_only_customer(): void
    {
        $plan = Customer::factory()->create();
        $response = $this->get('api/customers/' . $plan->id);

        $response->assertStatus(200);
    }

    public function test_failure_only_plan(): void
    {
        $response = $this->get('api/customers/999');

        $response->assertStatus(404);
    }


    public function test_insert_new_customer(): void
    {
        // Generate a new plan using the factory
        $planData = Customer::factory()->make()->toArray();

        // Send a POST request to create a new plan
        $response = $this->postJson('/api/customers', $planData);

        // Check if the response status is 201 Created
        $response->assertStatus(201);

        // Assert that the plan has been inserted into the database
        $this->assertDatabaseHas('customers', $planData);
    }

    public function test_fail_for_validate_insert_new_plan(): void
    {
        $planData = [];
        // Send a POST request to create a new plan
        $response = $this->postJson('/api/customers', $planData);

        // Check if the response status is 422
        $response->assertStatus(422);

        //  // Assert that the plan has been inserted into the database
        //  $this->assertDatabaseHas('customers', $planData);
    }



    public function test_update_a_plan(): void
    {
        // Generate a new plan using the factory
        $planData = Customer::factory()->make()->toArray();
        $planDataUpdate = Customer::factory()->make()->toArray();

        // Send a POST request to create a new plan
        $this->postJson('/api/customers', $planData);

        $planInserted = Customer::first();

        // Send a PUT request to update a plan
        $response = $this->putJson('/api/customers/' . $planInserted->id, $planDataUpdate);

        // Check if the response status is 201 Created
        $response->assertStatus(200);

        // Assert that the plan has been inserted into the database
        $this->assertDatabaseHas('customers', $planDataUpdate);
    }



    public function test_fail_for_validate_update_plan(): void
    {
        // Generate a new plan using the factory
        $planData = Customer::factory(
            [
                'short_name' => 'unique'
            ]
        )->make()->toArray();

        $this->postJson('/api/customers', $planData);

        $planInserted = Customer::first();
        $updateData = [];

        // Send a PUT request to update a plan
        $response = $this->putJson('/api/customers/' . $planInserted->id, $updateData);

        // Check if the response status is 422

        $response->assertStatus(422);


    }

    public function test_delete_plan()
    {
        $planData = Customer::factory()->make()->toArray();
        $this->postJson('/api/customers', $planData);
        $planInserted = Customer::first();
        // Send a PUT request to update a plan
        $response = $this->deleteJson('/api/customers/' . $planInserted->id);

        // Check if the response status is 200

        $response->assertStatus(200);


    }
}
