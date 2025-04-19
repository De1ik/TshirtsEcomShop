<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ShippingInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::with(['items.variant.product.activeDiscount'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('order.checkout', compact('cart'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'country' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'postcode' => 'nullable|string',
            'phone' => 'required|string',
            'payment_method' => 'required|in:cash,google_pay,apple_pay,paypal',
        ]);

        DB::transaction(function () use ($user, $request) {
            // 1. Save shipping info
            ShippingInfo::updateOrCreate(
                ['user_id' => $user->id],
                $request->only(['country', 'city', 'address', 'phone'])
            );

            // 2. Fetch active cart
            $cart = Cart::with(['items.variant.product.activeDiscount'])
                ->where('user_id', $user->id)
                ->firstOrFail();

            $subtotal = 0;
            $discountTotal = 0;
            $deliveryFee = 5;

            foreach ($cart->items as $item) {
                $original = $item->unit_price;
                $discount = $item->variant->product->activeDiscount;
                $final = $discount ? $discount->new_price : $original;

                $subtotal += $final * $item->quantity;
                if ($discount) {
                    $discountTotal += ($original - $final) * $item->quantity;
                }
            }

            $total = $subtotal + $deliveryFee;

            // 3. Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_date' => Carbon::today(),
                'delivery_fee' => $deliveryFee,
                'total_amount' => $total,
                'status' => 'pending',
            ]);

            // 4. Create order items
            foreach ($cart->items as $item) {
                $discount = $item->variant->product->activeDiscount;
                $price = $discount ? $discount->new_price : $item->unit_price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->variant->product->id,
                    'variant_id' => $item->variant->id,
                    'quantity' => $item->quantity,
                    'price_by_one' => $price,
                ]);
            }

            // 5. Create payment record
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
            ]);

            Cart::findOrFail($cart->id)->delete();
        });

        return redirect()->route('submitted.order')->with('success', 'Order placed using ' . $request->payment_method . '!');
    }
}
