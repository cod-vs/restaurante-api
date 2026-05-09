<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'producto_id', 'quantity', 'unit_price'];

    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->unit_price;
    }

    public function order() :BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function producto() :BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }


}
