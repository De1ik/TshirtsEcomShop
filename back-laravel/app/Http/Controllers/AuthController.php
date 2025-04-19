<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register() {
        return view('auth.register');
    }

    public function login() {
        return view('auth.login');
    }

    public function store() {
        $validated = request()->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8'
        ]);

        $user = User::create(
            [
                'email' => $validated['email'],
                'password_hash' => Hash::make($validated['password'])
            ]
        );

        return redirect()->route('login');
    }

    public function authenticate() {
        $validated = request()->validate(
            [
                'email' => 'required|email',
                'password' => 'required|min:8'
            ]
        );

        if (auth()->attempt($validated)) {
            request()->session()->regenerate();

            $this->mergeSessionCartIntoDatabase(auth()->user());

            // ✅ Transfer guest orders
            $this->transferGuestOrdersToUser(auth()->user());

            return redirect('/');
        }

        return redirect()->route('login')->withErrors([
            'email' => 'No matching users found with the provided email and password.',
        ]);
    }

    protected function transferGuestOrdersToUser($user) {
        $userOrderIds = session()->get('guest_orders', []);

        if (!empty($userOrderIds)) {
            Order::whereIn('id', $userOrderIds)
                ->whereNull('user_id')
                ->update(['user_id' => $user->id]);
            session()->forget('guest_orders');
        }
    }

    protected function mergeSessionCartIntoDatabase($user) {
        $sessionCart = session()->get('cart', []);

        if (empty($sessionCart)) {
            return;
        }

        $cart = $user->cart()->firstOrCreate([]);

        foreach ($sessionCart as $item) {
            $existingItem = $cart->items()
                ->where('product_variant_id', $item['variant_id'])
                ->first();

            if ($existingItem) {
                $existingItem->quantity += $item['quantity'];
                $existingItem->total_price = $existingItem->quantity * $existingItem->unit_price;
                $existingItem->save();
            } else {
                $cart->items()->create([
                    'product_variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);
            }
        }

        session()->forget('cart');
    }

    public function logout() {
        auth()->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
