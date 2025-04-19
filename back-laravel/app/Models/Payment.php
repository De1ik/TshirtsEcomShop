<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method',
        'payment_status',
        'payment_date',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    const METHOD_CARD = 'card';
    const METHOD_PAYPAL = 'paypal';
    const METHOD_CASH = 'cash';

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getMethodLabel()
    {
        return ucfirst($this->payment_method);
    }
}
