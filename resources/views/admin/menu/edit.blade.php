@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-soft">
            <div class="card-body p-4">
                <h2 class="fw-bold mb-4">Edit Menu Item</h2>
                <form method="POST" action="{{ route('admin.menu.update', $item) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3"><label class="form-label fw-semibold">Name</label><input name="name" value="{{ old('name', $item->name) }}" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Category</label><input name="category" value="{{ old('category', $item->category) }}" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Price</label><input name="price" type="number" step="0.01" value="{{ old('price', $item->price) }}" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Image Path or URL</label><input name="image_url" value="{{ old('image_url', $item->image_url) }}" class="form-control"></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="4">{{ old('description', $item->description) }}</textarea></div>
                    <div class="form-check mb-4"><input type="checkbox" name="is_available" value="1" class="form-check-input" id="available" {{ old('is_available', $item->is_available) ? 'checked' : '' }}><label class="form-check-label" for="available">Available</label></div>
                    <div class="d-flex gap-2"><button class="btn btn-primary">Update Item</button><a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary">Cancel</a></div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
