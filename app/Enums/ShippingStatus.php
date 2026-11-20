<?php

namespace App\Enums;

enum ShippingStatus: string
{
    //pending','processing','shipped','delivered','failed','returned'
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case RETURNED = 'returned';
    case DELIVERED = 'delivered';
    case FAILED = 'failed';
}
