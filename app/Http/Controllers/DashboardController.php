<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard', [
            'activeUsers' => User::where('is_active', true)->count(),
            'openOrders' => Order::where('status', '!=', Order::STATUS_DELIVERED)->count(),
            'archivedOrders' => Order::onlyTrashed()->count(),
        ]);
    }
}
