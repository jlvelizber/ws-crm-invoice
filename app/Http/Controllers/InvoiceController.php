<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\RepositoryInterface\InvoiceRepositoryInterface;
use App\Service\InvoiceService;

class InvoiceController extends Controller
{

    public function __construct(private readonly InvoiceService $invoiceRepositoryInterface)
    {
        $this->invoiceRepositoryInterface = $invoiceRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $this->invoiceRepositoryInterface->deleteInvoice($invoice);

        return response()->json(['message' => 'Invoice deleted'], 200);
    }
}
