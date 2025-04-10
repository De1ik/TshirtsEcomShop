<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscountProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'new_price',
        'date_start',
        'date_end',
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

