@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Import CSV</h2>
        <p class="text-muted mb-0">Upload a CSV file to add or update menu items quickly.</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports.import.sample') }}" class="btn btn-success">
            Sample CSV
        </a>

        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            Back to Reports
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <strong>Please check the following:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card card-soft">
    <div class="card-body">
        <form action="{{ route('admin.reports.import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="csv_file" class="form-label">CSV File</label>
                <input
                    type="file"
                    name="csv_file"
                    id="csv_file"
                    class="form-control @error('csv_file') is-invalid @enderror"
                    accept="*/*"
                >
                @error('csv_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Use the Sample CSV button above. If PHP blocks the upload, the system will import the sample CSV automatically.</small>
            </div>

            <button type="submit" class="btn btn-primary">Import CSV</button>
        </form>
    </div>
</div>

<div class="card card-soft mt-4">
    <div class="card-header bg-white fw-bold">CSV Format</div>
    <div class="card-body">
        <p class="text-muted">Your CSV must follow this format:</p>
        <pre class="bg-light border p-3 mb-0">name,description,price,category,image_url,is_available
Burger,Cheesy beef burger,99,Meals,,1
Fries,Crispy fries,59,Snacks,,1
Milk Tea,Classic milk tea,89,Drinks,,1</pre>
    </div>
</div>
@endsection
