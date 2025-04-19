<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function show($id) {
        $order = Order::with([
            'items.variant.product.mainImage',
            'items.variant.color',
            'payment',
        ])->where('user_id', Auth::id())->findOrFail($id);

        $shipping = Auth::user()->shippingInfo;

        $subtotal = 0;
        $discount = 0;

        foreach ($order->items as $item) {
            $product = $item->variant->product;
            $activeDiscount = $product->activeDiscount;
            $original = $item->price_by_one;
            $final = $activeDiscount ? $activeDiscount->new_price : $original;

            $subtotal += $original * $item->quantity;

            if ($activeDiscount) {
                $discount += ($original - $final) * $item->quantity;
            }
        }

        $payment = $order->payment;

        return view('order.submitted_order', compact('order', 'shipping', 'subtotal', 'discount', 'payment'));
    }
}
