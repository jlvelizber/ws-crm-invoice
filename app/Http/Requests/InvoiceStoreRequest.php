<?php

namespace App\Http\Requests;

use App\Enums\SourceCreateInvoiceEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return [
            'customer' => 'required | array',
            'customer.name' => 'required| string',
            'customer.identification' => 'required|string',
            'customer.email' => 'required|string|email',
            'customer.address' => 'nullable|string',
            'customer.phone' => 'nullable|string',
            'order_id' => 'nullable|integer|unique:invoices,order_id',
            'subtotal' => 'required|numeric',
            'tax' => 'required|numeric',
            // 'invoice_status' => 'required|string',
            'additional_info' => 'nullable|json',
            'source' => ['required', 'string', Rule::in(array_column(SourceCreateInvoiceEnum::cases(), 'value'))],
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
