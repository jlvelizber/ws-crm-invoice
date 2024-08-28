<?php

namespace App\Service;

use App\Class\SRIManager;
use App\Class\XMLFormatter;
use App\Enums\SRI\SRIDocumentTypeEnum;
use App\Interface\SRIServiceInterface;
use App\Models\Customer;
use App\Models\Invoice;
use App\RepositoryInterface\CustomerRepositoryInterface;
use App\RepositoryInterface\InvoiceRepositoryInterface;
use Illuminate\Http\Request;

class InvoiceService implements SRIServiceInterface
{
    protected InvoiceRepositoryInterface $invoiceRepositoryInterface;
    protected CustomerRepositoryInterface $customerRepositoryInterface;
    protected SRIManager $sriManager;

    protected XMLFormatter $xmlFormatter;

    public function __construct(
        InvoiceRepositoryInterface $invoiceRepositoryInterface,
        SRIManager $sriManager,
        XMLFormatter $xmlFormatter,
        CustomerRepositoryInterface $customerRepositoryInterface
    ) {
        $this->invoiceRepositoryInterface = $invoiceRepositoryInterface;
        $this->sriManager = $sriManager;
        $this->xmlFormatter = $xmlFormatter;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
    }


    public function manageCustomer(array $data): Customer
    {
        $identification = $data["identification"];
        $customer = $this->customerRepositoryInterface->findByNumIdentification($identification);
        if (!$customer) {
            $newCustomer = $this->customerRepositoryInterface->create($data);
            return $newCustomer;
        }
        return $this->customerRepositoryInterface->update($data, $customer->id);
    }


    /**
     * Save Invoice
     *
     * @param Request $request
     * @return void
     */
    public function process(Request $request): Invoice|bool
    {
        $customerData = $request->get('customer');

        $customer = $this->manageCustomer($customerData);


        $restPayload = $request->except('customer');

        $restPayload['customer_id'] = $customer->id;

        $invoice = $this->invoiceRepositoryInterface->create($restPayload);

        if (!$invoice)
            return false;

        $dataUpdateInvoice = $invoice->toArray();

        $docymentType = SRIDocumentTypeEnum::INVOICE;

        $acessKeyCode = $this->sriManager->generateAccessKeyCode($invoice, $docymentType);

        $dataUpdateInvoice['access_key'] = $acessKeyCode;

        // Actualiza
        $invoice = $this->invoiceRepositoryInterface->update($dataUpdateInvoice, $invoice->id);

        // Formatea Invoice to XML and sign It
        $xml = $this->xmlFormatter->generateInvoice($invoice);
        // Send Reception and Confirmation to SRI 
        $this->sriManager->sendToSRI($xml, $invoice->access_key);

        //
        return $invoice;
    }
}
