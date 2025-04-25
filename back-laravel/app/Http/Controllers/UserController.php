<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function profile()
    {
        $user = auth()->user();
        $shipping = $user->shippingInfo;
        $orders = $user->orders()->with('items')->latest()->get();
        return view('users.profile', compact('user', 'shipping', 'orders'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        Log::info('Updating user profile', ['user_id' => $user->id, 'request_data' => $request->all()]);

        $validatedUser = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'gender'     => 'nullable|in:male,female',
        ]);

        $validatedShipping = $request->validate([
            'phone'    => 'nullable|string|max:20|regex:/^\+?[0-9]*$/',
            'country'  => 'nullable|string|max:100',
            'city'     => 'nullable|string|max:100',
            'address'  => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:20|regex:/^[0-9]*$/',
        ]);

        Log::info('Validated data', ['user' => $validatedUser, 'shipping' => $validatedShipping]);

        $user->update($validatedUser);

        if ($user->shippingInfo) {
            $user->shippingInfo->update($validatedShipping);
        } else {
            $user->shippingInfo()->create($validatedShipping);
        }

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }
}
