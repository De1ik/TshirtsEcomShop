<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function index() {
        $user = Auth::user();
        $orders = Order::all()->where('user_id', $user);


        return view('order.cart');
    }
}
