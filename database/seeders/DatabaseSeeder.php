<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'jorgeconsalvacion@gmail.com',
            'role' => UserRoleEnum::ADMIN
        ]);

        $this->call(PlanSeeder::class);
        $this->call(CustomerSeeder::class);
    }
}
