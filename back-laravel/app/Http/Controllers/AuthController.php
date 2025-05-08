<?php

namespace App\Http\Controllers;

use app\Enums\Role;
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
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $existingUser = User::where('email', $validated['email'])->first();

        if ($existingUser) {
            if ($existingUser->role === \App\Enums\Role::GUEST) {
                $existingUser->update([
                    'password_hash' => Hash::make($validated['password']),
                    'role' => \App\Enums\Role::USER
                ]);
            } else {
                return redirect()->route('register')->withErrors([
                    'email' => 'This email is already taken.',
                ]);
            }
        } else {
            User::create([
                'email' => $validated['email'],
                'password_hash' => Hash::make($validated['password']),
                'role' => \App\Enums\Role::USER
            ]);
        }

        return redirect()->route('login')->with('success', 'Account created successfully. Please log in.');
    }

    public function authenticate() {
        $validated = request()->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $user = User::where('email', $validated['email'])->first();

        // Check if user exists and is not a guest
        if ($user && $user->role === 'guest') {
            return redirect()->route('login')->withErrors([
                'email' => 'Guest accounts cannot be used to log in.',
            ]);
        }

        if ($user && Hash::check($validated['password'], $user->password_hash)) {
            auth()->login($user);
            request()->session()->regenerate();

            $this->mergeSessionCartIntoDatabase($user);

            return redirect('/');
        }

        return redirect()->route('login')->withErrors([
            'email' => 'No matching users found with the provided email and password.',
        ]);
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
