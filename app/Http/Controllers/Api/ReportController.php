<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;

class ReportController extends Controller
{
    public function orders()
    {
        $totalSales = Order::where('status', 'delivered')->sum('total_amount');
        $totalOrders = Order::count();
        $byStatus = Order::selectRaw('status, count(*) as count')->groupBy('status')->get();
        $bestSelling = OrderItem::selectRaw('menu_item_id, sum(quantity) as total_qty')
            ->with('menuItem')
            ->groupBy('menu_item_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return response()->json([
            'message' => 'Orders report fetched successfully.',
            'data' => compact('totalSales', 'totalOrders', 'byStatus', 'bestSelling'),
        ]);
    }

    public function export()
    {
        return response()->json([
            'message' => 'Orders JSON export generated successfully.',
            'data' => Order::with('user', 'orderItems.menuItem')->latest()->get(),
        ]);
    }
}
