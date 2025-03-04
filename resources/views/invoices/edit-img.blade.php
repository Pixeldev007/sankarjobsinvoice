@extends('layouts.app')

@section('title')
  Edit Images
@endsection

@section('content')
    @php $styleCss = 'style'; @endphp
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div id="fileUploadFormContainer">
                <div class="card-body">
                    {{ Form::open(['route' => ['invoices.img-edit', $invoice->id], 'id' => 'invoiceForm', 'name' => 'invoiceForm', 'files' => true, 'method' => 'POST']) }}
                    
                    <div class="col-lg-3 col-sm-12 mb-5">
                        {{ Form::label('file', __('Upload Multiple Add Photos') . ':', ['class' => 'form-label mb-3']) }}
                        {{ Form::file('file[]', ['class' => 'form-control', 'id' => 'file', 'autocomplete' => 'off', 'multiple' => true]) }}

                        <!-- Submit Field -->
                        <div class="float-start py-5">
                            <div class="form-group ">
                                <button type="submit" name="save" class="btn btn-primary" id="editSave2">{{ __('messages.common.save') }}</button>
                             </div>
                        </div>
                    </div>
                    
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
