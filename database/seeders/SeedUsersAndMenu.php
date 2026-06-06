<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SeedUsersAndMenu extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        $customer = User::updateOrCreate(
            ['email' => 'customer@example.com'],
            ['name' => 'Customer User', 'password' => Hash::make('password'), 'role' => 'customer']
        );

        $rider = User::updateOrCreate(
            ['email' => 'rider@example.com'],
            ['name' => 'Rider User', 'password' => Hash::make('password'), 'role' => 'rider']
        );

        $items = [
            [
                'name' => 'Classic Burger',
                'description' => 'Juicy burger with cheese, lettuce, tomatoes, onions, and special sauce.',
                'price' => 120,
                'category' => 'Main Dish',
                'image_url' => '/images/menu/classic-burger.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Crispy Fries',
                'description' => 'Golden fried potato fries with crispy seasoning.',
                'price' => 50,
                'category' => 'Sides',
                'image_url' => '/images/menu/crispy-fries.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Fried Chicken',
                'description' => 'Crunchy fried chicken served hot and crispy.',
                'price' => 180,
                'category' => 'Main Dish',
                'image_url' => '/images/menu/fried-chicken.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Spaghetti',
                'description' => 'Sweet-style spaghetti with tomato sauce and cheese.',
                'price' => 140,
                'category' => 'Pasta',
                'image_url' => '/images/menu/pasta.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Iced Tea',
                'description' => 'Refreshing house iced tea served cold.',
                'price' => 40,
                'category' => 'Beverage',
                'image_url' => '/images/menu/iced-tea.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Rice Meal',
                'description' => 'Savory rice meal with flavorful toppings.',
                'price' => 100,
                'category' => 'Main Dish',
                'image_url' => '/images/menu/rice-meal.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Pizza Slice',
                'description' => 'Cheesy pepperoni pizza slice with rich tomato sauce.',
                'price' => 90,
                'category' => 'Snack',
                'image_url' => '/images/menu/pizza-slice.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Milk Tea',
                'description' => 'Sweet milk tea drink with pearls.',
                'price' => 80,
                'category' => 'Beverage',
                'image_url' => '/images/menu/milk-tea.jpg',
                'is_available' => true,
            ],
        ];

        foreach ($items as $item) {
            MenuItem::updateOrCreate(['name' => $item['name']], $item);
        }

        if (Order::count() === 0) {
            $burger = MenuItem::where('name', 'Classic Burger')->first();
            $tea = MenuItem::where('name', 'Iced Tea')->first();

            $order = Order::create([
                'user_id' => $customer->id,
                'total_amount' => 0,
                'status' => 'approved',
                'delivery_address' => 'Sample Address, Lingayen, Pangasinan',
                'contact_number' => '09123456789',
                'notes' => 'Sample seeded order',
            ]);

            $total = 0;
            foreach ([[$burger, 2], [$tea, 1]] as [$menu, $qty]) {
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
        }
    }
}
