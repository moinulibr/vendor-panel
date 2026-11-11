<?php

namespace App\Enums;

enum SaleStatus: string
{
    case DRAFT = 'draft';
    case QUOTATION = 'quotation';
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case RETURNED = 'returned';
    case CANCELLED = 'cancelled';
    case REJECTED = 'rejected';
    case REFUNDED = 'refunded';
}
