<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMenu = MenuItem::count();
        $totalOrders = Order::count();
        $pending = Order::where('status', 'pending')->count();
        $delivered = Order::where('status', 'delivered')->count();
        $totalSales = Order::where('status', 'delivered')->sum('total_amount');
        $recentOrders = Order::with('user')->latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalMenu',
            'totalOrders',
            'pending',
            'delivered',
            'totalSales',
            'recentOrders'
        ));
    }
}
