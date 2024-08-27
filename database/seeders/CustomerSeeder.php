<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    private const FINAL_CUSTOMER = 'CONSUMIDOR FINAL';
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::truncate();
        Customer::create(
            [
                'identification' => '9999999999999',
                'name' => self::FINAL_CUSTOMER,
            ]
        );
        Customer::factory()->count(10)->create();
    }
}
