<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_variant_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    public function cart(){
        return $this->belongsTo(Cart::class);
    }

    public function variant(){
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function product(){
        return $this->variant->product();
    }
}
