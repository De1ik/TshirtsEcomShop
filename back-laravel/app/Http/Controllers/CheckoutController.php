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
use Illuminate\Support\Facades\Validator;
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

        $delivery_method = $request->input('delivery_method', session('delivery_method', 'courier'));
        $delivery_fee = (float) $request->input('delivery_fee', session('delivery_fee', 5));

        session(['delivery_method' => $delivery_method, 'delivery_fee' => $delivery_fee]);

        return view('order.checkout', compact('cart', 'shipping_info', 'delivery_method', 'delivery_fee'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'          => 'required|email',
            'country'        => 'required|string',
            'city'           => 'required|string',
            'address'        => 'required|string',
            'postcode'       => 'nullable|string|max:20|regex:/^[0-9]*$/',
            'phone'          => 'required|string|max:20|regex:/^\+?[0-9]*$/',
            'payment_method' => 'required|in:cash,google_pay,apple_pay,paypal',
        ]);

        if ($validator->fails()) {
            // Логування помилок валідації
            Log::warning('Checkout validation failed', $validator->errors()->toArray());

            // Повернення назад з повідомленнями та old input
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        Log::info('✅ Checkout validation passed', $data);

        $order = null;
        Log::info("⚙️ Checkout started...");

        try {
            DB::transaction(function () use ($request, &$order) {
                $deliveryFee = (float) session('delivery_fee', 5);
                $deliveryOption = session('delivery_method', 'courier');

                $subtotal = 0;
                $discountTotal = 0;

                Log::info("📦 Creating or fetching user...");
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


                Log::info("✅ User ID: {$user->id}");

                $paidMethods = ['paypal', 'google_pay', 'apple_pay'];
                $orderStatus = in_array($request->payment_method, $paidMethods) ? 'processing' : 'pending';

                Log::info("📄 Creating order...");
                $order = Order::create([
                    'user_id' => $user->id,
                    'order_date' => Carbon::today(),
                    'delivery_fee' => $deliveryFee,
                    'total_amount' => 0,
                    'status' => $orderStatus,
                    'delivery_option' => $deliveryOption,
                ]);

                $cart = Auth::check()
                    ? Cart::with(['items.variant.product.activeDiscount'])->where('user_id', $user->id)->first()
                    : session()->get('cart', []);

                // Валідація кошика
                if (!$cart || (is_object($cart) && $cart->items->isEmpty()) || (is_array($cart) && count($cart) === 0)) {
                    throw new \Exception('Cart is empty or not found.');
                }

                $items = isset($cart->items) ? $cart->items : collect($cart);

                foreach ($items as $item) {
                    if (isset($item->variant)) {
                        $variant = $item->variant;
                        $product = $variant->product;
                        $price = $variant->product->activeDiscount?->new_price ?? $item->unit_price;
                        $quantity = $item->quantity;
                    } else {
                        $variant = ProductVariant::with('product')->find($item['variant_id']);
                        if (!$variant) {
                            throw new \Exception('Product variant not found: ' . $item['variant_id']);
                        }
                        $product = $variant->product;
                        $price = $variant->product->activeDiscount?->new_price ?? $item['unit_price'];
                        $quantity = $item['quantity'];
                    }

                    // Перевірка кількості в наявності
                    if ($variant->amount < $quantity) {
                        throw new \Exception("❌ Not enough stock for variant ID {$variant->id} (requested: $quantity, available: {$variant->amount})");
                    }


                    Log::info("Stock before: variant {$variant->id} has {$variant->amount}");

                    $variant->decrement('amount', $quantity);

                    $newAmount = ProductVariant::find($variant->id)->amount;
                    Log::info("Stock after: variant {$variant->id} has {$newAmount}");

                    $subtotal += $price * $quantity;
                    $discountTotal += ($product->price - $price) * $quantity;

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

                Log::info("💸 Subtotal: $subtotal, Discount: $discountTotal, Delivery: $deliveryFee");

                ShippingInfo::updateOrCreate(
                    ['user_id' => $user->id],
                    $request->only(['country', 'city', 'address', 'phone', 'postcode'])
                );

                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $request->payment_method,
                    'payment_status' => in_array($request->payment_method, $paidMethods) ? 'paid' : 'pending',
                ]);

                if (Auth::check()) {
                    Cart::where('user_id', $user->id)->delete();
                }
                session()->forget('cart');
            });
            if (!$order || !$order->id) {
                throw new \Exception('Order creation failed.');
            }

            return redirect()->route('order.details', ['id' => $order->id])
                ->with('success', 'Order successfully placed!');

        } catch (\Exception $e) {
            Log::error('❗Checkout error: ' . $e->getMessage());
            return redirect()->route('checkout')->with('error', 'Something went wrong during checkout. Please try again.');
        }
    }
}
