<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurante extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'address'];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
