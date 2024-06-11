<?php

namespace App\Enums;

enum InvoiceStatusEnum: string
{
    case PENDING = 'pending';
    case SENT = 'sent';
    case SIGNED = 'signed';
    case authorized = 'authorized';
}
