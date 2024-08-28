<?php

namespace App\Enums;

enum SourceCreateInvoiceEnum: string
{
    case LOCAL = 'local';
    case EXTERNAL = 'external';
}
