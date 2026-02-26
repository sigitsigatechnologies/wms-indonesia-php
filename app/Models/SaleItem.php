<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'sale_items';
    
    public $timestamps = false;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'selling_price',
        'cost_price_snapshot',
        'profit',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'cost_price_snapshot' => 'decimal:2',
        'profit' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
