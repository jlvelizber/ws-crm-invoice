<?php

namespace App\Http\Requests\Wp;

use App\Http\Requests\InvoiceStoreRequest;

class WPApiStoreInvoiceRequest extends InvoiceStoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


}
