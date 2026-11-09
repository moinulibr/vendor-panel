<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'version',
        'is_current',
        'invoice_no',
        'channel',
        'source_ref',
        'customer_id',
        'customer_type',
        'warehouse_id',
        'sales_person_id',
        'sub_total',
        'item_total_discount',
        'gross_total',
        'discount_type',
        'discount_value',
        'discount_amount',
        'coupon_id',
        'coupon_discount_type',
        'coupon_discount_value',
        'coupon_discount_amount',
        'shipping_charge',
        'tax_amount',
        'round_off',
        'final_amount',
        'paid_amount',
        'due_amount',
        'sale_date',
        'status',
        'payment_status',
        'shipping_status',
        'quote_valid_until',
        'quote_converted_at',
        'quote_converted_by',
        'quote_notes',
        'currency',
        'exchange_rate',
        'notes',
        'is_editable_for_admin',
        'is_editable_for_staff',
        'is_editable_for_customer',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'version' => 'integer',
        'is_current' => 'boolean',
        'sub_total' => 'decimal:2',
        'item_total_discount' => 'decimal:2',
        'gross_total' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'coupon_discount_value' => 'decimal:2',
        'coupon_discount_amount' => 'decimal:2',
        'shipping_charge' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'round_off' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:2',
        'sale_date' => 'datetime',
        'quote_valid_until' => 'date',
        'quote_converted_at' => 'datetime',
        'is_editable_for_admin' => 'boolean',
        'is_editable_for_staff' => 'boolean',
        'is_editable_for_customer' => 'boolean',
    ];

    // --------------------------------------------------------------------------
    // Scopes
    // --------------------------------------------------------------------------

    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where('is_current', true);
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePartial(Builder $query): Builder
    {
        return $query->where('payment_status', 'partially_paid');
    }

    // --------------------------------------------------------------------------
    // Relationships
    // --------------------------------------------------------------------------

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class, 'sale_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SalePayment::class, 'sale_id');
    }

    public function returns(): HasMany
    {
        return $this->hasMany(SaleReturn::class, 'sale_id');
    }

    public function taxes(): HasMany
    {
        return $this->hasMany(SaleTax::class, 'sale_id');
    }

    public function editHistory(): HasMany
    {
        return $this->hasMany(SaleEditHistory::class, 'sale_id');
    }

    public function editItems(): HasMany
    {
        return $this->hasMany(SaleEditItem::class, 'sale_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'parent_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(Sale::class, 'parent_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function salesperson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_person_id');
    }

    public function paymentSummary(): HasOne
    {
        return $this->hasOne(SalePaymentSummary::class, 'sale_id');
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function quoteConvertedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'quote_converted_by');
    }
}
