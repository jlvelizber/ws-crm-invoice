<?php

namespace App\Service;

use App\Class\SRIManager;
use App\Class\XMLFormatter;
use App\Enums\SRI\SRIDocumentTypeEnum;
use App\Interface\SRIServiceInterface;
use App\Models\Invoice;
use App\Repository\InvoiceRepository;
use Illuminate\Http\Request;

class InvoiceService implements SRIServiceInterface
{
    protected InvoiceRepository $invoiceRepository;
    protected SRIManager $sriManager;

    protected XMLFormatter $xmlFormatter;

    public function __construct(InvoiceRepository $invoiceRepository, SRIManager $sriManager, XMLFormatter $xmlFormatter)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->sriManager = $sriManager;
        $this->xmlFormatter = $xmlFormatter;
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
     * Get Invoice
     */
    public function getInvoice(string|int $id): Invoice
    {
        return $this->invoiceRepository->find($id);
    }


    /**
     * Update invoice
     */
    public function updateInvoice(Request $request, string|int $id)
    {
        $data = $request->all();

        return $this->invoiceRepository->update($data, $id);
    }

    /**
     * Delete Invoice
     */
    public function deleteInvoice(Invoice $invoice): void
    {
        $this->invoiceRepository->delete($invoice);
    }


    public function manageCustomer(array $data)
    {

    }


    /**
     * Save Invoice
     *
     * @param Request $request
     * @return void
     */
    public function process(Request $request): Invoice|bool
    {
        $customer = $request->get('customer');



        $restPayload = $request->except('customer');

        $invoice = $this->invoiceRepository->create($restPayload);

        if (!$invoice)
            return false;

        $dataUpdateInvoice = $invoice->toArray();

        $docymentType = SRIDocumentTypeEnum::INVOICE;

        $acessKeyCode = $this->sriManager->generateAccessKeyCode($invoice, $docymentType);

        $dataUpdateInvoice['access_key'] = $acessKeyCode;

        // Actualiza
        $invoice = $this->invoiceRepository->update($dataUpdateInvoice, $invoice->id);

        // Formatea Invoice to XML and sign It
        $xml = $this->xmlFormatter->generateInvoice($invoice);
        // Send Reception and Confirmation to SRI 
        $this->sriManager->sendToSRI($xml, $invoice->access_key);

        //
        return $invoice;
    }
}
