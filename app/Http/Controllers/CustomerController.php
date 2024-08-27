<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\RepositoryInterface\CustomerRepositoryInterface;

class CustomerController extends Controller
{


    public function __construct(private CustomerRepositoryInterface $customerRepositoryInterface)
    {
        $this->customerRepositoryInterface = $customerRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = $this->customerRepositoryInterface->all();
        return CustomerResource::collection($customers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        try {
            $customer = $this->customerRepositoryInterface->create($request->all());
            return CustomerResource::make($customer);

        } catch (\Throwable $th) {
            return response($th->getMessage(), $th->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $customer = $this->customerRepositoryInterface->find($customer->id);
        return CustomerResource::make($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        try {
            $customer = $this->customerRepositoryInterface->update($request->all(), $customer->id);
            return CustomerResource::make($customer);
        } catch (\Throwable $th) {
            return response($th->getMessage(), $th->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        try {
            $this->customerRepositoryInterface->delete($customer->id);
            return response()->json(['message' => 'Customer deleted succesfully'], 200);
        } catch (\Throwable $th) {
            return response($th->getMessage(), $th->getCode());
        }
    }
}
