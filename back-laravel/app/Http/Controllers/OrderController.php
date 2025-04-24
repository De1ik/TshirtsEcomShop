<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Fluent;

class OrderController extends Controller
{
    public function show($id) {
        if (!Auth::check()) {
            $guestEmail = session('guest_email');
            $user = User::where('email', $guestEmail)->first();
        } else {
            $user = Auth::user();
        }
        $order = Order::with([
            'items.variant.product.mainImage',
            'items.variant.color',
            'payment',
        ])->where('user_id', Auth::id())->findOrFail($id);

        $shipping = $order->user?->shippingInfo ?? new Fluent([
            'country' => '',
            'city' => '',
            'address' => '',
            'postcode' => '',
            'phone' => '',
        ]);

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
