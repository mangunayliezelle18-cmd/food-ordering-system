@extends('layouts.guest')

@section('card')
<div class="text-center mb-4">
    <h2 class="fw-bold mb-1">Create Customer Account</h2>
    <p class="text-muted mb-0">Register to place and track your food orders.</p>
</div>

<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label fw-semibold">Full Name</label>
        <input name="name" type="text" value="{{ old('name') }}" class="form-control" required autofocus autocomplete="name">
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold">Email Address</label>
        <input name="email" type="email" value="{{ old('email') }}" class="form-control" required autocomplete="username">
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold">Password</label>
        <input name="password" type="password" class="form-control" required autocomplete="new-password">
    </div>
    <div class="mb-4">
        <label class="form-label fw-semibold">Confirm Password</label>
        <input name="password_confirmation" type="password" class="form-control" required autocomplete="new-password">
    </div>
    <button class="btn btn-primary w-100 py-2 fw-bold">Register</button>
</form>

<p class="text-center mt-4 mb-0">
    Already registered? <a href="{{ route('login') }}">Login</a>
</p>
@endsection
