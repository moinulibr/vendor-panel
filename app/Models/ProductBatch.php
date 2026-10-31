<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductBatch extends Model
{
    use HasFactory;

    protected $table = 'product_batches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'batch_no',
        'product_id',
        'variation_id',
        'vendor_id',
        'warehouse_id',
        'purchase_bill_id',
        'purchase_item_id',
        'purchase_receipt_id',
        'purchase_receipt_item_id',
        'mfg_date',
        'expiry_date',
        'supplier_batch_no',
        'qty_received',
        'qty_available',
        'qty_reserved',
        'cost_price',
        'selling_price_ref',
        'mrp',
        'status',
        'serial_range',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'mfg_date' => 'date',
        'expiry_date' => 'date',
        'qty_received' => 'integer',
        'qty_available' => 'integer',
        'qty_reserved' => 'integer',
        'cost_price' => 'decimal:2',
        'selling_price_ref' => 'decimal:2',
        'mrp' => 'decimal:2',
    ];

    // --------------------------------------------------------------------------
    // Relationships
    // --------------------------------------------------------------------------

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class, 'variation_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function summary(): HasOne
    {
        return $this->hasOne(BatchSummary::class, 'product_batch_id');
    }

    public function saleItemBatches(): HasMany
    {
        return $this->hasMany(SaleItemBatch::class, 'batch_id');
    }
}
