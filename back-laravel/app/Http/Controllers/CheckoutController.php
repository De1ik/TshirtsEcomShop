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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
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

        $delivery_method = session('delivery_option', 'courier');

        return view('order.checkout', compact('cart', 'shipping_info', 'delivery_method'));
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
        Log::info("Before try");
        try {
            DB::transaction(function () use ($request, &$order) {
                $deliveryFee = 5;
                $subtotal = 0;
                $discountTotal = 0;

                Log::info("Creating user or using Auth user...");
                $user = Auth::check()
                    ? Auth::user()
                    : User::firstOrCreate(
                        ['email' => $request->email],
                        [
                            'password_hash' => Hash::make(Str::random(16)),
                            'role' => 'guest'
                        ]
                    );

                if (!Auth::check()) {
                    session()->put('guest_email', $request->email);
                }


                Log::info("User ID: " . $user->id);

                Log::info("Creating order...");
                $order = Order::create([
                    'user_id' => $user->id, // nullable
                    'order_date' => Carbon::today(),
                    'delivery_fee' => $deliveryFee,
                    'total_amount' => 0,
                    'status' => 'pending',
                    'delivery_option' => session('delivery_option', 'courier'),
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

                $paidMethods = ['card', 'paypal', 'google_pay', 'apple_pay'];

                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $request->payment_method,
                    'payment_status' => in_array($request->payment_method, $paidMethods) ? 'paid' : 'pending',
                ]);

                Cart::where('user_id', $user->id)->delete();
                session()->forget('cart');
            });
        } catch (\Exception $e) {
            Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->route('checkout')->with('error', 'Something went wrong during checkout.');
        }

        if (!$order) {
            return redirect()->route('checkout')->with('error', 'Something went wrong while creating the order.');
        }

        return redirect()->route('order.details', ['id' => $order->id])
            ->with('success', 'Order successfully placed!');
    }
}
