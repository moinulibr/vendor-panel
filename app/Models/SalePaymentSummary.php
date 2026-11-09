<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalePaymentSummary extends Model
{
    use HasFactory;

    protected $table = 'sale_payment_summary';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
        'total_paid',
        'total_refunded',
        'net_paid',
        'last_payment_at',
        'payment_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_paid' => 'decimal:2',
        'total_refunded' => 'decimal:2',
        'net_paid' => 'decimal:2',
        'last_payment_at' => 'datetime',
        'payment_count' => 'integer',
    ];

    // --------------------------------------------------------------------------
    // Relationships
    // --------------------------------------------------------------------------

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
