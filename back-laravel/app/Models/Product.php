<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'name',
        'description',
        'price',
        'final_price',
        'is_discount',
        'category',
        'gender',
    ];

    protected $casts = [
        'is_discount' => 'boolean',
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function discount()
    {
        return $this->hasOne(DiscountProduct::class);
    }

    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main', true);
    }

    public function activeDiscount()
    {
        return $this->hasOne(DiscountProduct::class)
                    ->whereDate('date_start', '<=', now())
                    ->whereDate('date_end', '>=', now());
    }
}

