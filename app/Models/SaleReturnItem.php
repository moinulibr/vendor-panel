<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleReturnItem extends Model
{
    use HasFactory;

    protected $table = 'sale_return_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'return_id',
        'sale_item_id',
        'batch_id',
        'product_id',
        'variation_id',
        'unit_price_snapshot',
        'qty_returned',
        'line_refund',
        'line_disc_reversal',
        'inv_disc_reversal',
        'tax_reversal',
        'net_refund',
        'restock',
        'condition',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'qty_returned' => 'integer',
        'unit_price_snapshot' => 'decimal:2',
        'line_refund' => 'decimal:2',
        'line_disc_reversal' => 'decimal:2',
        'inv_disc_reversal' => 'decimal:2',
        'tax_reversal' => 'decimal:2',
        'net_refund' => 'decimal:2',
        'restock' => 'boolean',
    ];

    // --------------------------------------------------------------------------
    // Relationships
    // --------------------------------------------------------------------------

    public function saleReturn(): BelongsTo
    {
        return $this->belongsTo(SaleReturn::class, 'return_id');
    }

    public function return(): BelongsTo
    {
        return $this->belongsTo(SaleReturn::class, 'return_id');
    }

    public function saleItem(): BelongsTo
    {
        return $this->belongsTo(SaleItem::class, 'sale_item_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class, 'variation_id');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(ProductBatch::class, 'batch_id');
    }
}
