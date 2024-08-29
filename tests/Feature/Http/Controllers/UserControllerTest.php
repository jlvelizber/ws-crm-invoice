<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }
    /**
     * A basic feature test example.
     */
    public function test_get_all_users(): void
    {
        $response = $this->get('/api/users');

        $response->assertStatus(200);
    }



    public function test_get_only_user(): void
    {
        $model = User::factory()->create();
        $response = $this->get('api/users/' . $model->id);

        $response->assertStatus(200);
    }


    public function test_failure_only_user(): void
    {
        $response = $this->get('api/users/999');

        $response->assertStatus(404);
    }


    public function test_insert_new_user(): void
    {
        // Generate a new plan using the factory
        $payload =
            [
                'password' => '12345678',
                'password_confirmation' => '12345678',
                'name' => $this->faker()->name(),
                'email' => $this->faker()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'role' => $this->faker()->randomElement(array_column(UserRoleEnum::cases(), 'value')),
                //     // 'password_confirmation' => '12345678',
            ];

        // Send a POST request to create a new plan
        $response = $this->postJson('/api/users', $payload);
        // dd($response);

        // Check if the response status is 201 Created
        $response->assertStatus(201);
    }

    public function test_fail_for_validate_insert_new_user(): void
    {
        $payload = [];
        // Send a POST request to create a new plan
        $response = $this->postJson('/api/users', $payload);

        // Check if the response status is 422
        $response->assertStatus(422);
    }



    public function test_update_an_user(): void
    {
        // Generate a new plan using the factory
        $payload =
            [
                'password' => '12345678',
                'password_confirmation' => '12345678',
                'name' => $this->faker()->name(),
                'email' => $this->faker()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'role' => $this->faker()->randomElement(array_column(UserRoleEnum::cases(), 'value')),
                //     // 'password_confirmation' => '12345678',
            ];
        $payloadUpdate = [
            'name' => $this->faker()->name(),
        ];

        // Send a POST request to create a new plan
        $this->postJson('/api/users', $payload);

        $model = User::first();

        // Send a PUT request to update a plan
        $response = $this->putJson('/api/users/' . $model->id, $payloadUpdate);

        // Check if the response status is 201 Updated
        $response->assertStatus(200);

        // Assert that the plan has been inserted into the database
        $this->assertNotEquals($model->name, $payloadUpdate['name']);
    }


    public function test_delete_user()
    {
        $payload =
            [
                'password' => '12345678',
                'password_confirmation' => '12345678',
                'name' => $this->faker()->name(),
                'email' => $this->faker()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'role' => $this->faker()->randomElement(array_column(UserRoleEnum::cases(), 'value')),
            ];

        $this->postJson('/api/users', $payload);
        $model = User::first();
        // Send a PUT request to update a plan
        $response = $this->deleteJson('/api/users/' . $model->id);

        $response->assertStatus(200);


    }


    public function test_failure_delete_user()
    {
        $response = $this->deleteJson('/api/users/45454');

        $response->assertStatus(404);


    }


}
