<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'invoice_number',
        'issue_date',
        'customer_id',
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
