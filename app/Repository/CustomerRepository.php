<?php
namespace App\Repository;

use App\RepositoryInterface\CustomerRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomerRepository implements CustomerRepositoryInterface
{
    /**
     * Get all invoices
     */
    public function all()
    {
        return Customer::all();
    }

    /**
     * Store a new customer
     *
     * @param array $data
     * @return Customer | null
     */
    public function create(array $data): Customer|Exception
    {
        //code...
        $customer = Customer::create($data);
        if (!$customer)
            throw new \Exception('No se pudo crear el cliente', 500);
        return $customer;

    }

    /**
     * Update a customer
     */
    public function update(array $data, string|int $id): Customer
    {
        $customer = Customer::findOrFail($id);
        $customer->fill($data);
        $customer->update();
        $customer = Customer::findOrFail($id);
        return $customer;
    }

    /**
     * Delete a customer
     */
    public function delete(string|int $id): bool|Exception
    {
        $this->find($id)->delete();
        return true;
    }

    /**
     * Get Customer
     * @param string | int $id
     * @return Model | null
     */
    public function find(string|int $id): Model|Exception
    {
        $customer = Customer::findOrFail($id);
        if (!$customer)
            throw new ModelNotFoundException('Cliente no existe', 404);
        return $customer;
    }

    public function findByNumIdentification(string $numIdentification, $columns = ['*']): Customer|null
    {
        $customer = Customer::where('identification', $numIdentification)->first($columns);
        if (!$customer)
            return null;
        return $customer;
    }
}
