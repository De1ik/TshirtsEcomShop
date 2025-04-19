<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function index() {
        $user = Auth::user();

        $cart = Cart::with([
            'items.variant.product.activeDiscount',
            'items.variant.color'
        ])
            ->where('user_id', $user->id)
            ->first();

        return view('order.cart', compact('cart'));
    }

    public function increaseQuantity($id)
    {
        $item = CartItem::findOrFail($id);
        $item->quantity += 1;
        $item->total_price = $item->quantity * $item->unit_price;
        $item->save();

        return redirect()->back();
    }

    public function decreaseQuantity($id)
    {
        $item = CartItem::findOrFail($id);

        if ($item->quantity > 1) {
            $item->quantity -= 1;
            $item->total_price = $item->quantity * $item->unit_price;
            $item->save();
        } else {
            $item->delete();
        }

        return redirect()->back();
    }


    public function removeItem($id)
    {
        CartItem::findOrFail($id)->delete();
        return redirect()->back();
    }
}
