<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Orders fetched successfully.',
            'data' => Order::with('user', 'orderItems.menuItem')->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'exists:menu_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'delivery_address' => ['nullable', 'string', 'max:500'],
            'contact_number' => ['nullable', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $order = DB::transaction(function () use ($data) {
            $order = Order::create([
                'user_id' => $data['user_id'],
                'total_amount' => 0,
                'status' => 'pending',
                'delivery_address' => $data['delivery_address'] ?? null,
                'contact_number' => $data['contact_number'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {
                $menu = MenuItem::findOrFail($item['id']);
                $qty = (int) $item['quantity'];
                $subtotal = $menu->price * $qty;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menu->id,
                    'quantity' => $qty,
                    'price' => $menu->price,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $order->update(['total_amount' => $total]);

            return $order->load('user', 'orderItems.menuItem');
        });

        return response()->json([
            'message' => 'Order created successfully.',
            'data' => $order,
        ], 201);
    }

    public function show(Order $order)
    {
        return response()->json([
            'message' => 'Order fetched successfully.',
            'data' => $order->load('user', 'orderItems.menuItem'),
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['sometimes', Rule::in(Order::STATUSES)],
            'delivery_address' => ['nullable', 'string', 'max:500'],
            'contact_number' => ['nullable', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $order->update($data);

        return response()->json([
            'message' => 'Order updated successfully.',
            'data' => $order->fresh()->load('user', 'orderItems.menuItem'),
        ]);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully.',
        ]);
    }
}
