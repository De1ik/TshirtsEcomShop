<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'release_date',
    ];

    protected $casts = [
        'release_date' => 'date',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

