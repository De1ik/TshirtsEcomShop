<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'country',
        'city',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

