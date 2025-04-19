<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $cart = $this->getSessionCart();
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
        $variantId = $request->input('variant_id');
        $quantity = $request->input('quantity', 1);
        $variant = ProductVariant::with('product', 'color')->findOrFail($variantId);

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
            $key = (string) $variantId;

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
