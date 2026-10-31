<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchSummary extends Model
{
    use HasFactory;

    protected $table = 'batch_summaries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_batch_id',
        'product_id',
        'variation_id',
        'warehouse_id',
        'vendor_id',
        'total_received',
        'total_sold',
        'total_returned',
        'total_wasted',
        'total_damaged',
        'total_expired',
        'total_reserved',
        'total_available',
        'total_purchase_cost',
        'total_cogs',
        'total_revenue',
        'total_profit',
        'total_loss',
        'profit_margin_percent',
        'last_updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_received' => 'integer',
        'total_sold' => 'integer',
        'total_returned' => 'integer',
        'total_wasted' => 'integer',
        'total_damaged' => 'integer',
        'total_expired' => 'integer',
        'total_reserved' => 'integer',
        'total_available' => 'integer',
        'total_purchase_cost' => 'decimal:2',
        'total_cogs' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'total_profit' => 'decimal:2',
        'total_loss' => 'decimal:2',
        'profit_margin_percent' => 'decimal:2',
        'last_updated_at' => 'datetime',
    ];

    // --------------------------------------------------------------------------
    // Relationships
    // --------------------------------------------------------------------------

    public function batch(): BelongsTo
    {
        return $this->belongsTo(ProductBatch::class, 'product_batch_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class, 'variation_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
