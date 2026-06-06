<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'exists:menu_items,id'],
            'items.*.quantity' => ['nullable', 'integer', 'min:0'],
            'delivery_address' => ['required', 'string', 'max:500'],
            'contact_number' => ['required', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $selectedItems = collect($data['items'])
            ->filter(function ($item) {
                return isset($item['quantity']) && (int) $item['quantity'] > 0;
            })
            ->values();

        if ($selectedItems->isEmpty()) {
            return back()
                ->withErrors(['items' => 'Please choose at least one menu item before placing an order.'])
                ->withInput();
        }

        $order = DB::transaction(function () use ($data, $selectedItems) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => 0,
                'status' => 'pending',
                'delivery_address' => $data['delivery_address'],
                'contact_number' => $data['contact_number'],
                'notes' => $data['notes'] ?? null,
            ]);

            $total = 0;

            foreach ($selectedItems as $item) {
                $menu = MenuItem::where('is_available', true)->findOrFail($item['id']);
                $quantity = (int) $item['quantity'];
                $subtotal = $menu->price * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menu->id,
                    'quantity' => $quantity,
                    'price' => $menu->price,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $order->update([
                'total_amount' => $total,
            ]);

            return $order;
        });

        return redirect()
            ->route('customer.orders.show', $order)
            ->with('success', 'Order placed successfully. You can now track your order here.');
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.menuItem')
            ->latest()
            ->get();

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $order->load('orderItems.menuItem');

        return view('customer.orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        if (! in_array($order->status, ['pending', 'approved'], true)) {
            return back()->withErrors([
                'status' => 'This order can no longer be cancelled.',
            ]);
        }

        $order->update([
            'status' => 'cancelled',
        ]);

        return redirect()
            ->route('customer.orders.show', $order)
            ->with('success', 'Order cancelled successfully.');
    }
}