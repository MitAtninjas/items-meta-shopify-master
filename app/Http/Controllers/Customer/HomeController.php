<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('customer.dashboard');
    }
}
