<?php

namespace App\Enums;

enum InvoiceStatusEnum: string
{
    case PENDING = 'pending';
    case SENT = 'sent';
    case SIGNED = 'signed';
    case authorized = 'authorized';


    case SRI_WDSL_STATUS_RECIEVED = 'RECIBIDA';
    case SRI_WDSL_STATUS_AUTHORIZED = 'AUTORIZADO';
}
