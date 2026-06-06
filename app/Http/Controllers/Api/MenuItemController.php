<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Menu items fetched successfully.',
            'data' => MenuItem::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'max:255'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $data['is_available'] = $request->boolean('is_available', true);

        $item = MenuItem::create($data);

        return response()->json([
            'message' => 'Menu item created successfully.',
            'data' => $item,
        ], 201);
    }

    public function show(MenuItem $menuItem)
    {
        return response()->json([
            'message' => 'Menu item fetched successfully.',
            'data' => $menuItem,
        ]);
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'category' => ['sometimes', 'string', 'max:255'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        if ($request->has('is_available')) {
            $data['is_available'] = $request->boolean('is_available');
        }

        $menuItem->update($data);

        return response()->json([
            'message' => 'Menu item updated successfully.',
            'data' => $menuItem->fresh(),
        ]);
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();

        return response()->json([
            'message' => 'Menu item deleted successfully.',
        ]);
    }
}
