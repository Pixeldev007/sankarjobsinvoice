@extends('layouts.app')
@section('title')
    {{ __('messages.client.edit_client') }}
@endsection
@section('content')
    @php $styleCss = 'style'; @endphp
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex flex-column">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-end mb-5">
                    <h1>@yield('title')</h1>
                    <a class="btn btn-outline-primary float-end"
                        href="{{ route('clients.index') }}">{{ __('messages.common.back') }}</a>
                </div>
                <div class="col-12">
                    @include('layouts.errors')
                </div>
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['clients.update', $client->id], 'method' => 'put', 'files' => 'true', 'id' => 'editClientForm']) }}
                        @include('clients.edit_fields')
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
      {{ Form::hidden('is_edit', true, ['id' => 'isEdit']) }}
@endsection
@section('phone_js')

@endsection
