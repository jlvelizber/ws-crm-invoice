<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wp_order_id',
        'invoice_number',
        'issue_date',
        'customer_name',
        'customer_identification',
        'customer_address',
        'customer_email',
        'subtotal',
        'tax',
        'total',
        'access_key',
        'authorization_code',
        'environment',
        'invoice_status',
        'additional_info',
        'source',
        'external_order_id',
        'external_customer_id',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function sriResponse()
    {
        return $this->hasOne(SriResponse::class);
    }
}
