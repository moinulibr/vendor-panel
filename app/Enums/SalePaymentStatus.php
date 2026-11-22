<?php

namespace App\Enums;

enum SalePaymentStatus: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case REVERSED = 'reversed';
}
