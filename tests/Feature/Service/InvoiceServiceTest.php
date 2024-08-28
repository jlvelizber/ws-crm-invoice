<?php

namespace Tests\Feature\Service;

use App\Models\Customer;
use App\Service\InvoiceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceServiceTest extends TestCase
{
    use RefreshDatabase;
    protected InvoiceService $invoiceService;

    public function setUp(): void
    {
        parent::setUp();
        $this->invoiceService = $this->app->make(InvoiceService::class);
    }

    /**
     * Assert if customer was created
     * @return void
     */
    public function test_manage_customer_was_created(): void
    {
        $customerData = Customer::factory()->make()->toArray();
        $this->invoiceService->manageCustomer($customerData);
        $this->assertDatabaseHas("customers", $customerData);
    }

    /**
     * Assert if customer was updated
     * @return void
     */
    public function test_manage_customer_was_updated(): void
    {
        $customerData = Customer::factory([
            'identification' => '0926894544'
        ])->make()->toArray();
        Customer::create($customerData);
        $updateCustomerData['email'] = 'jorgeconsalvacion@gmail.com';
        $updateCustomerData['identification'] = '0926894544';
        $this->invoiceService->manageCustomer($updateCustomerData);

        $this->assertNotEquals($customerData['email'], $updateCustomerData['email']);
    }
}
