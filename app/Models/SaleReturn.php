<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleReturn extends Model
{
    use HasFactory;

    protected $table = 'sale_returns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'return_no',
        'sale_id',
        'customer_id',
        'warehouse_id',
        'return_type',
        'subtotal_refund',
        'item_disc_reversal',
        'inv_disc_reversal',
        'tax_reversal',
        'shipping_refund',
        'restocking_fee',
        'total_refund',
        'settlement_type',
        'status',
        'reason',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal_refund' => 'decimal:2',
        'item_disc_reversal' => 'decimal:2',
        'inv_disc_reversal' => 'decimal:2',
        'tax_reversal' => 'decimal:2',
        'shipping_refund' => 'decimal:2',
        'restocking_fee' => 'decimal:2',
        'total_refund' => 'decimal:2',
    ];

    // --------------------------------------------------------------------------
    // Relationships
    // --------------------------------------------------------------------------

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleReturnItem::class, 'return_id');
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(SaleRefund::class, 'return_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
