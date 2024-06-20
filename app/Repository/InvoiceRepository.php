<?php

namespace App\Repository;

use App\Interface\InvoiceRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    /**
     * Get all invoices
     */
    public function all()
    {
        //
    }

    /**
     * Store a new invoice
     *
     * @param array $data
     * @return Model | null
     */
    public function create(array $data): Model | null
    {
        try {
            //code...
            $invoice = Invoice::create($data);
            // Save items to invoice
            $invoice->items()->createMany($data['items']);

            return $invoice;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage(), $th->getCode());
        }
    }

    /**
     * Update a invoice
     */
    public function update(array $data, string | int $id): Model
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->fill($data);
        $invoice->update();
        $invoice = Invoice::findOrFail($id);
        return $invoice;
    }

    /**
     * Delete a invoice
     */
    public function delete(Model $invoice): bool
    {
        $invoice->forceDelete();
        return true;
    }

    /**
     * Get Invoice
     * @param string | int $id
     * @return Model | null
     */
    public function find(string | int $id): Model | null
    {
        try {
            return Invoice::findOrFail($id);
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage(), $th->getCode());
        }
    }
}
