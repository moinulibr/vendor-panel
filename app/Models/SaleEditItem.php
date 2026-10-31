<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleEditItem extends Model
{
    use HasFactory;

    protected $table = 'sale_edit_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
        'sale_item_id',
        'change_type',
        'old_qty',
        'new_qty',
        'old_price',
        'new_price',
        'old_discount',
        'new_discount',
        'changed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_qty' => 'integer',
        'new_qty' => 'integer',
        'old_price' => 'decimal:2',
        'new_price' => 'decimal:2',
        'old_discount' => 'decimal:2',
        'new_discount' => 'decimal:2',
        'changed_at' => 'datetime',
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
}
