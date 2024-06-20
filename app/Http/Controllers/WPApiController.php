<?php

namespace App\Http\Controllers;

use App\Http\Requests\Wp\WPApiStoreInvoiceRequest;
use App\Models\Invoice;
use App\Service\InvoiceService;
use Illuminate\Http\Request;

class WPApiController extends Controller
{
    protected InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService) {
        $this->invoiceService = $invoiceService;
    }
    /**
     * Check if API is available
     *
     * @return void
     */
    public function checkHealth()
    {
        return response()->json(['status' => 'ok'], 200);
    }

    /**
     * Handle API request of invoices from WP
     *
     * @param WPApiStoreInvoiceRequest $request
     */
    public function invoices(WPApiStoreInvoiceRequest $request)
    {
        $invoice = $this->invoiceService->saveInvoice($request);

        return response()->json(['status' => 'success', 'invoice' => $invoice], 201);
     
    }
}
