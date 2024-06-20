<?php

namespace App\Http\Controllers;

use App\Http\Requests\Wp\WPApiStoreInvoiceRequest;
use App\Models\Invoice;
use Illuminate\Http\Request;

class WPApiController extends Controller
{
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
        $invoice = Invoice::create($request->all());
        // Save items to invoice
        $invoice->items()->createMany($request->items);

        return response()->json(['status' => 'success', 'invoice' => $invoice], 201);
     
    }
}
