<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{

    public function index() {
//         $request->file('image')->store('products', 'public');
        return view('index');
    }
}
