<?php

namespace App\Http\Requests\Wp;

use Illuminate\Foundation\Http\FormRequest;

class WPApiStoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'wp_order_id' => 'nullable|integer|unique:invoices,wp_order_id',
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'issue_date' => 'required|date',
            'customer_name' => 'required|string',
            'customer_identification' => 'required|string',
            'customer_address' => 'nullable|string',
            'customer_email' => 'nullable|string|email',
            'subtotal' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
            'environment' => 'required|string',
            'invoice_status' => 'required|string',
            'additional_info' => 'nullable|json',
            'source' => 'required|string',
            'external_order_id' => 'nullable|integer|unique:invoices,external_order_id',
            'external_customer_id' => 'nullable|string',
            //validate items with the keys description, quantity, and price
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer',
            'items.*.unit_price' => 'required|numeric', 
            'items.*.total' => 'required|numeric',
            'items.*.discount' => 'required|numeric',
            
        ];
    }
}
