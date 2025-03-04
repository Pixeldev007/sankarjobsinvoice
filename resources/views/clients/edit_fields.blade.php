<!-- resources/views/clients/create.blade.php -->
<div class="row gx-10 mb-5">
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('first_name', __('messages.client.first_name') . ':', ['class' => 'form-label required mb-3']) }}
            {{ Form::text('first_name', $client->user->first_name ?? null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.client.first_name'), 'required']) }}
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('last_name', __('messages.client.last_name') . ':', ['class' => 'form-label required mb-3']) }}
            {{ Form::text('last_name', $client->user->last_name ?? null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.client.last_name'), 'required']) }}
        </div>
    </div>
          {{ Form::hidden('email', $client->user->email ?? null, ['class' => 'form-control form-control-solid', 'placeholder' => __('messages.client.email'),]) }}
 
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('address', __('messages.client.address') . ':', ['class' => 'form-label mb-3']) }}
            {{ Form::textarea('address', $client->address ?? null, ['class' => 'form-control form-control-solid', 'rows' => '5', 'placeholder' => __('messages.client.address')]) }}
        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-5">
            {{ Form::label('notes', __('messages.client.notes') . ':', ['class' => 'form-label mb-3']) }}
            {{ Form::textarea('note', $client->note ?? null, ['class' => 'form-control form-control-solid', 'rows' => '5', 'placeholder' => __('messages.client.notes')]) }}
        </div>
    </div>
    <div class="col-lg-12 mb-7">
        {{ Form::label('company_name', __('messages.setting.company_name') . ':', ['class' => 'form-label mb-3']) }}
        {{ Form::text('company_name', $client->company_name ?? null, ['class' => 'form-control', 'placeholder' => __('messages.setting.company_name')]) }}
    </div>
    <div class="d-flex justify-content-end mt-5">
        {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-3']) }}
        <a href="{{ route('clients.index') }}" type="reset" class="btn btn-secondary btn-active-light-primary">{{ __('messages.common.cancel') }}</a>
    </div>
</div>
