<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{

    public function index() {
        if (Auth::check()) {
            $user = Auth::user();

            $cart = Cart::with([
                'items.variant.product.activeDiscount',
                'items.variant.color'
            ])
                ->where('user_id', $user->id)
                ->first();
        } else {
            $sessionItems = $this->getSessionCart();

            $cart = new \stdClass();
            $cart->items = collect();

            foreach ($sessionItems as $sessionItem) {
                $variant = ProductVariant::with('product.activeDiscount', 'color')->find($sessionItem['variant_id']);
                if ($variant) {
                    $cart->items->push((object)[
                        'id' => $sessionItem['variant_id'],
                        'variant' => $variant,
                        'quantity' => $sessionItem['quantity'],
                        'unit_price' => $sessionItem['unit_price'],
                        'total_price' => $sessionItem['unit_price'] * $sessionItem['quantity'],
                    ]);
                }
            }
        }

        return view('order.cart', compact('cart'));
    }


    public function increaseQuantity($id)
    {
        if (Auth::check()) {
            $item = CartItem::findOrFail($id);
            $item->quantity += 1;
            $item->total_price = $item->quantity * $item->unit_price;
            $item->save();
        } else {
            $cart = $this->getSessionCart();
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] += 1;
                $this->saveSessionCart($cart);
            }
        }

        return redirect()->back();
    }

    public function addToCart(Request $request)
    {
        Log::info("Attempting to add to cart");

        $productId = $request->input('product_id');
        $colorName = strtolower($request->input('color'));
        $size = $request->input('size');
        $quantity = $request->input('quantity', 1);

        $product = Product::with('variants.color')->findOrFail($productId);
        Log::info("Attempting to add to cart", [
            'product_id' => $productId,
            'color' => $colorName,
            'size' => $size,
            'requested_quantity' => $quantity
        ]);


        $variant = $product->variants->first(function ($variant) use ($colorName, $size) {
            return strtolower($variant->color->name) === $colorName && $variant->size === $size;
        });

        if (!$variant) {
            Log::warning("Variant not found", ['color' => $colorName, 'size' => $size]);
            return redirect()->back()->with('error', 'Variant with selected size and color does not exist.');
        }

        Log::info("Found variant", [
            'variant_id' => $variant->id,
            'amount' => $variant->stock
        ]);

        if ($variant->amount < $quantity) {
            Log::warning("Not enough stock", [
                'variant_id' => $variant->id,
                'amount' => $variant->stock,
                'requested_quantity' => $quantity
            ]);
            return redirect()->back()->with('error', 'We do not have enough stock for this variant.');
        }

        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            $item = CartItem::where('cart_id', $cart->id)
                ->where('product_variant_id', $variant->id)
                ->first();

            if ($item) {
                $item->quantity += $quantity;
                $item->total_price = $item->quantity * $item->unit_price;
                $item->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $quantity,
                    'unit_price' => $variant->product->final_price,
                    'total_price' => $variant->product->final_price * $quantity,
                ]);
            }
        } else {
            $cart = $this->getSessionCart();
            $key = (string) $variant->id;

            if (isset($cart[$key])) {
                $cart[$key]['quantity'] += $quantity;
            } else {
                $cart[$key] = [
                    'variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'product_id' => $variant->product->id,
                    'size' => $variant->size,
                    'color_hex' => $variant->color->hex_code ?? '#000',
                    'unit_price' => $variant->product->final_price,
                    'quantity' => $quantity,
                    'image_url' => $variant->product->mainImage->image_url ?? null,
                ];
            }

            $this->saveSessionCart($cart);
        }

        return redirect()->back()->with('success', 'Item added to cart.');
    }


    public function decreaseQuantity($id)
    {
        if (Auth::check()) {
            $item = CartItem::findOrFail($id);
            if ($item->quantity > 1) {
                $item->quantity -= 1;
                $item->total_price = $item->quantity * $item->unit_price;
                $item->save();
            } else {
                $item->delete();
            }
        } else {
            $cart = $this->getSessionCart();
            if (isset($cart[$id])) {
                if ($cart[$id]['quantity'] > 1) {
                    $cart[$id]['quantity'] -= 1;
                } else {
                    unset($cart[$id]);
                }
                $this->saveSessionCart($cart);
            }
        }

        return redirect()->back();
    }

    public function removeItem($id)
    {
        if (Auth::check()) {
            CartItem::findOrFail($id)->delete();
        } else {
            $cart = $this->getSessionCart();
            if (isset($cart[$id])) {
                unset($cart[$id]);
                $this->saveSessionCart($cart);
            }
        }

        return redirect()->back();
    }

    protected function getSessionCart() {
        return session()->get('cart', []);
    }

    protected function saveSessionCart(array $cart) {
        session()->put('cart', $cart);
    }
}
