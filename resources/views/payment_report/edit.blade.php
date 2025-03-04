@extends('layouts.app')

@section('content')
<div class="container">
                <div class="d-flex justify-content-between align-items-end mb-5">
                    <h1>@yield('title')</h1>
                    <a class="btn btn-outline-primary float-end"
                       href="{{ url()->previous() }}">{{ __('messages.common.back') }}</a>
                </div>
    <h1>Edit Payment Method</h1>
    <form action="{{ route('payment_methods.update', $paymentMethod->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group"  style="margin: 30px 0px;">
            <label for="payment_method">Payment Method</label>
            <input type="text" name="payment_method" class="form-control" value="{{ $paymentMethod->payment_method }}" style="width: 250px; margin: 10px 0px;">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection