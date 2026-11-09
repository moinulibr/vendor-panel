<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SaleItem extends Model
{
    use HasFactory;

    protected $table = 'sale_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
        'product_id',
        'variation_id',
        'vendor_id',
        'product_name_snapshot',
        'variant_label_snapshot',
        'invoice_name_snapshot',
        'image_snapshot',
        'unit_price_snapshot',
        'customer_type_snapshot',
        'qty',
        'line_subtotal',
        'allow_discount',
        'line_discount',
        'line_discount_percent',
        'taxable_amount',
        'tax_type_snapshot',
        'tax_rate_snapshot',
        'tax_amount',
        'allocated_inv_disc',
        'line_total',
        'vendor_commission_type_snapshot',
        'vendor_commission_value_snapshot',
        'vendor_commission_amount',
        'vendor_payable_amount',
        'salesperson_commission_type_snapshot',
        'salesperson_commission_value_snapshot',
        'salesperson_commission_amount',
        'is_combo',
        'status',
        'line_note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'qty' => 'integer',
        'unit_price_snapshot' => 'decimal:2',
        'line_subtotal' => 'decimal:2',
        'allow_discount' => 'boolean',
        'line_discount' => 'decimal:2',
        'line_discount_percent' => 'decimal:2',
        'taxable_amount' => 'decimal:2',
        'tax_rate_snapshot' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'allocated_inv_disc' => 'decimal:2',
        'line_total' => 'decimal:2',
        'vendor_commission_value_snapshot' => 'decimal:2',
        'vendor_commission_amount' => 'decimal:2',
        'vendor_payable_amount' => 'decimal:2',
        'salesperson_commission_value_snapshot' => 'decimal:2',
        'salesperson_commission_amount' => 'decimal:2',
        'is_combo' => 'boolean',
    ];

    // --------------------------------------------------------------------------
    // Scopes
    // --------------------------------------------------------------------------

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    // --------------------------------------------------------------------------
    // Relationships
    // --------------------------------------------------------------------------

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

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

    public function batchAllocations(): HasMany
    {
        return $this->hasMany(SaleItemBatch::class, 'sale_item_id');
    }

    public function tax(): HasOne
    {
        return $this->hasOne(SaleTax::class, 'sale_item_id');
    }

    public function taxes(): HasMany
    {
        return $this->hasMany(SaleTax::class, 'sale_item_id');
    }

    public function returnItems(): HasMany
    {
        return $this->hasMany(SaleReturnItem::class, 'sale_item_id');
    }
}
