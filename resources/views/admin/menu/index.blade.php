@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Menu Management</h2>
        <p class="text-muted mb-0">Create, update, and delete menu items.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('admin.menu.create') }}">Add Item</a>
</div>

<form method="GET" class="mb-3">
    <div class="input-group">
        <input name="search" value="{{ request('search') }}" class="form-control" placeholder="Search menu...">
        <button class="btn btn-outline-secondary">Search</button>
    </div>
</form>

<div class="card card-soft">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th width="90">Image</th><th>Name</th><th>Category</th><th>Price</th><th>Available</th><th width="180">Actions</th></tr></thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>
                            @if($item->image_url)
                                <img src="{{ str_starts_with($item->image_url, 'http') ? $item->image_url : asset(ltrim($item->image_url, '/')) }}"
                                     alt="{{ $item->name }}"
                                     class="rounded"
                                     style="width: 70px; height: 55px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 70px; height: 55px;">🍽️</div>
                            @endif
                        </td>
                        <td><strong>{{ $item->name }}</strong><br><span class="text-muted small">{{ Str::limit($item->description, 60) }}</span></td>
                        <td>{{ $item->category }}</td>
                        <td>₱{{ number_format($item->price, 2) }}</td>
                        <td><span class="badge bg-{{ $item->is_available ? 'success' : 'secondary' }}">{{ $item->is_available ? 'Yes' : 'No' }}</span></td>
                        <td>
                            <a href="{{ route('admin.menu.edit', $item) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('admin.menu.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Delete this item?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No menu items found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $items->links() }}</div>
</div>
@endsection
