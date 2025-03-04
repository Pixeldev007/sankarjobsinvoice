@extends('layouts.app')
@section('title')
    {{ __('messages.payment_methods') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            <livewire:payment-method-table />
        </div>
    </div>
@endsection

