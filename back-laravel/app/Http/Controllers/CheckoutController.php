<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductVariant;
use App\Models\ShippingInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $shipping_info = auth()->user()->shippingInfo;
            $cart = Cart::with(['items.variant.product.activeDiscount'])
                ->where('user_id', Auth::id())
                ->firstOrFail();
        } else {
            $shipping_info = null;
            $cart = session()->get('cart', []);
        }

        return view('order.checkout', compact('cart', 'shipping_info'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'country' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'postcode' => 'nullable|string',
            'phone' => 'required|string',
            'payment_method' => 'required|in:cash,google_pay,apple_pay,paypal',
        ]);

        $order = null;

        DB::transaction(function () use ($request, &$order) {
            $deliveryFee = 5;
            $subtotal = 0;
            $discountTotal = 0;

            if (!Auth::check()) {
                $randomPassword = Str::random(16);

                $user = User::firstOrCreate(
                    ['email' => $request->email],
                    [
                        'password_hash' => Hash::make($randomPassword),
                        'role' => 'guest'
                    ]
                );
            } else {
                $user = Auth::user();
            }


            $order = Order::create([
                'user_id' => $user->id, // nullable
                'order_date' => Carbon::today(),
                'delivery_fee' => $deliveryFee,
                'total_amount' => 0,
                'status' => 'pending',
            ]);

            $cart = Cart::with(['items.variant.product.activeDiscount'])
                ->where('user_id', $user->id)
                ->first();

            $items = $cart ? $cart->items : collect(session()->get('cart', []));

            foreach ($items as $item) {
                if (isset($item->variant)) {
                    $variant = $item->variant;
                    $product = $variant->product;
                    $price = $variant->product->activeDiscount?->new_price ?? $item->unit_price;
                    $quantity = $item->quantity;
                } else {
                    // Session-based cart
                    $variant = ProductVariant::with('product')->findOrFail($item['variant_id']);
                    $product = $variant->product;
                    $price = $variant->product->activeDiscount?->new_price ?? $item['unit_price'];
                    $quantity = $item['quantity'];
                }


                $subtotal += $price * $quantity;
                $discountTotal += ($product->price - $price) * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'variant_id' => $variant->id,
                    'quantity' => $quantity,
                    'price_by_one' => $price,
                ]);

                $variant->amount -= $quantity;
                $variant->save();
            }

            $order->total_amount = $subtotal + $deliveryFee;
            $order->save();

            if ($user->password != null) {
                ShippingInfo::updateOrCreate(
                    ['user_id' => Auth::id()],
                    $request->only(['country', 'city', 'address', 'phone', 'postcode'])
                );
            }

            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
            ]);

            Cart::where('user_id', $user->id)->delete();
            session()->forget('cart');
        });

        return redirect()->route('order.details', ['id' => $order->id])->with('success', 'Order successfully placed!');
    }
}
