<?php

namespace App\Enums\SRI;

enum SRITaxeIVAFee: int
{
    case ZERO = 0;
    case TWELVE = 2;
    case FOURTEEN = 3;
    case FIFTEEN = 4;
    case FIVE = 5;
    case NO_OBJECT_IVA = 6;
    case EXCENT_IVA = 7;
    case IVA_DIFFERENCED = 8;
    case THIRTEEN = 10;
}
