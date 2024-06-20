<?php

namespace App\Service;

use App\Models\Invoice;
use App\Repository\InvoiceRepository;
use Illuminate\Http\Request;

class InvoiceService
{
    protected InvoiceRepository $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Get All invoices
     *
     * @return void
     */
    public function getAllInvoices()
    {
        return $this->invoiceRepository->all();
    }

    /**
     * Save Invoice
     *
     * @param Request $request
     * @return void
     */
    public function saveInvoice(Request $request): Invoice
    {
        $data = $request->all();

        $invoice = $this->invoiceRepository->create($data);

        return $invoice;
    }

    /**
     * Get Invoice
     */
    public function getInvoice(string |int $id): Invoice
    {
        return $this->invoiceRepository->find($id);
    }


    /**
     * Update invoice
     */
    public function updateInvoice(Request $request, string | int $id)
    {
        $data = $request->all();

        return $this->invoiceRepository->update($data, $id);
    }

    /**
     * Delete Invoice
     */
    public function deleteInvoice(string | int $id): void
    {
        $this->invoiceRepository->delete($id);
    }
}
