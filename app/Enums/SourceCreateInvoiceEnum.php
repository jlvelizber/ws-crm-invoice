<?php

namespace App\Enums;

enum SourceCreateInvoiceEnum : string
{
    case WORDPRESS = 'wordpress';
    case EXTERNAL = 'exexternal';
}
