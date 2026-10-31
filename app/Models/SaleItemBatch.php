<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItemBatch extends Model
{
    use HasFactory;

    protected $table = 'sale_item_batches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
        'sale_item_id',
        'batch_id',
        'qty_used',
        'unit_cost',
        'cost_total',
        'unit_sale_price',
        'total_sale_amount',
        'total_profit',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'qty_used' => 'integer',
        'unit_cost' => 'decimal:2',
        'cost_total' => 'decimal:2',
        'unit_sale_price' => 'decimal:2',
        'total_sale_amount' => 'decimal:2',
        'total_profit' => 'decimal:2',
    ];

    // --------------------------------------------------------------------------
    // Relationships
    // --------------------------------------------------------------------------

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function saleItem(): BelongsTo
    {
        return $this->belongsTo(SaleItem::class, 'sale_item_id');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(ProductBatch::class, 'batch_id');
    }
}
