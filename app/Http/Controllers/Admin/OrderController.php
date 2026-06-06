<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'rider', 'orderItems.menuItem'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['orderItems.menuItem', 'user', 'rider']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(Order::STATUSES)],
        ]);

        $updates = [
            'status' => $data['status'],
        ];

        if ($data['status'] === 'delivered' && ! $order->delivered_at) {
            $updates['delivered_at'] = now();
        }

        $order->update($updates);

        return redirect()
            ->back()
            ->with('success', 'Order status updated successfully.');
    }

    public function approve(Order $order)
    {
        $order->update([
            'status' => 'approved',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Order approved successfully.');
    }

    public function reject(Order $order)
    {
        $order->update([
            'status' => 'rejected',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Order rejected successfully.');
    }
}