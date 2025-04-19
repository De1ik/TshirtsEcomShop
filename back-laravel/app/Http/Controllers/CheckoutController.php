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
        if (Auth::check()) {
            $cart = Cart::with(['items.variant.product.activeDiscount'])
                ->where('user_id', Auth::id())
                ->firstOrFail();
        } else {
            $cart = session()->get('cart', []);
        }

        return view('order.checkout', compact('cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'postcode' => 'nullable|string',
            'phone' => 'required|string',
            'payment_method' => 'required|in:cash,google_pay,apple_pay,paypal',
        ]);

        DB::transaction(function () use ($request) {
            $deliveryFee = 5;
            $subtotal = 0;
            $discountTotal = 0;

            $order = Order::create([
                'user_id' => Auth::id(), // nullable
                'order_date' => Carbon::today(),
                'delivery_fee' => $deliveryFee,
                'total_amount' => 0,
                'status' => 'pending',
            ]);

            $items = Auth::check()
                ? Cart::with(['items.variant.product.activeDiscount'])
                    ->where('user_id', Auth::id())
                    ->firstOrFail()
                    ->items
                : session()->get('cart', []);

            foreach ($items as $item) {
                if (Auth::check()) {
                    $variant = $item->variant;
                    $product = $variant->product;
                    $discount = $product->activeDiscount;
                    $price = $discount ? $discount->new_price : $item->unit_price;
                    $quantity = $item->quantity;
                } else {
                    $variant = ProductVariant::with('product')->findOrFail($item['variant_id']);
                    $product = $variant->product;
                    $discount = $product->activeDiscount;
                    $price = $discount ? $discount->new_price : $item['unit_price'];
                    $quantity = $item['quantity'];
                }

                $subtotal += $price * $quantity;
                if ($discount) {
                    $discountTotal += (($variant->product->price - $price) * $quantity);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'variant_id' => $variant->id,
                    'quantity' => $quantity,
                    'price_by_one' => $price,
                ]);
            }

            $order->total_amount = $subtotal + $deliveryFee;
            $order->save();

            if (Auth::check()) {
                ShippingInfo::updateOrCreate(
                    ['user_id' => Auth::id()],
                    $request->only(['country', 'city', 'address', 'phone'])
                );
            }

            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
            ]);

            Auth::check() ? Cart::where('user_id', Auth::id())->delete() : session()->forget('cart');
            if (!Auth::check()) {
                session()->push('guest_orders', $order->id);
            }
        });

        return redirect()->route('submitted_order')->with('success', 'Order successfully placed!');
    }
}
