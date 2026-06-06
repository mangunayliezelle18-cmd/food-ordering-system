<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = MenuItem::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = $query->latest()->paginate(10)->withQueryString();

        return view('admin.menu.index', compact('items'));
    }

    public function create()
    {
        return view('admin.menu.create');
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

        $data['is_available'] = $request->boolean('is_available');

        MenuItem::create($data);

        return redirect()->route('admin.menu.index')->with('success', 'Menu item created successfully.');
    }

    public function show(MenuItem $menuItem)
    {
        return redirect()->route('admin.menu.edit', $menuItem);
    }

    public function edit(MenuItem $menuItem)
    {
        return view('admin.menu.edit', ['item' => $menuItem]);
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'max:255'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $data['is_available'] = $request->boolean('is_available');

        $menuItem->update($data);

        return redirect()->route('admin.menu.index')->with('success', 'Menu item updated successfully.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();

        return redirect()->route('admin.menu.index')->with('success', 'Menu item deleted successfully.');
    }
}
