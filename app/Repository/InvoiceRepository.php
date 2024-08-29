<?php

namespace App\Repository;

use App\RepositoryInterface\InvoiceRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    /**
     * Get all invoices
     */
    public function all(): Collection
    {
        return Invoice::all();
    }

    /**
     * Store a new invoice
     *
     * @param array $data
     * @return Model | null
     */
    public function create(array $data): Model|Exception
    {
        //code...
        $invoice = Invoice::create($data);
        // Save items to invoice
        $invoice->items()->createMany($data['items']);

        if (!$invoice) {
            throw new \Exception('No se ha podido guardar la factura');

        }

        return $invoice;

    }

    /**
     * Update a invoice
     */
    public function update(array $data, string|int $id): Model
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
    public function delete(int|string $invoiceId): bool
    {
        $this->find($invoiceId)->delete();
        return true;
    }

    /**
     * Get Invoice
     * @param string | int $id
     * @return Model | null
     */
    public function find(string|int $id): Model|ModelNotFoundException
    {

        $invoice = Invoice::findOrFail($id);
        if (!$invoice)
            throw new ModelNotFoundException('Factura no encontrada');
        return $invoice;
    }
}
