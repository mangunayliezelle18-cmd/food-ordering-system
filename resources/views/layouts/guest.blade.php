@extends('layouts.app')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-7 col-lg-5">
        <div class="card card-soft">
            <div class="card-body p-4 p-md-5">
                @yield('card')
            </div>
        </div>
    </div>
</div>
@endsection
