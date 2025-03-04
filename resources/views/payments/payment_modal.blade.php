@php
    use App\Models\payment_method;
    
     $paymentMethods = payment_method::pluck('payment_method', 'payment_method')->toArray();
@endphp

<div id="paymentModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('messages.payment.add_payment') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            {{ Form::open(['id'=>'paymentForm']) }}
            <div class="modal-body scroll-y">
                <div class="alert alert-danger display-none hide" id="editValidationErrorsBox"></div>
                <div class="row">
                 <div class="form-group col-lg-4 col-sm-12 mb-5">
                    <label for="invoice_id" class="form-label required mb-3">{{ __('messages.invoice.invoice') }}:</label>
                    <select id="invoice_id" name="invoice_id" class="form-select invoice" required data-control="select2">
                        <option value="" disabled selected>{{ __('messages.invoice.invoice') }}</option>
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}">
                                {{ $invoice->invoice_id }} - {{ $invoice->client->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                    <div class="form-group col-lg-4 col-sm-12 mb-5 ">
                        {{ Form::label('due_amount',__('messages.invoice.due_amount').':', ['class' => 'form-label mb-3']) }}
                        <div class="input-group">
                            {{ Form::text('due_amount', null, ['id'=>'due_amount','class' => 'form-control ','placeholder'=>__('messages.invoice.due_amount'),'readonly','disabled']) }}
                            <a class="input-group-text bg-secondary border-0 text-decoration-none invoice-currency-code" id="autoCode" href="javascript:void(0)"
                               data-toggle="tooltip"
                               data-placement="right" title="Currency Code">
                                {{ getCurrencySymbol() }}
                            </a>
                        </div>
                    </div>
                    <div class="form-group col-lg-4 col-sm-12 mb-5 ">
                        {{ Form::label('paid_amount',__('messages.invoice.paid_amount').':', ['class' => 'form-label mb-3']) }}
                        <div class="input-group">
                            {{ Form::text('paid_amount', null, ['id'=>'paid_amount','class' => 'form-control ','placeholder'=>__('messages.invoice.paid_amount'),'readonly','disabled']) }}
                            <a class="input-group-text bg-secondary border-0 text-decoration-none invoice-currency-code" id="autoCode" href="javascript:void(0)"
                               data-toggle="tooltip"
                               data-placement="right" title="Currency Code">
                                {{ getCurrencySymbol() }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-4 col-sm-12 mb-5">
                        {{ Form::label('payment_date', __('messages.payment.payment_date').(':'),['class' => 'form-label required mb-3']) }}
                        {{ Form::text('payment_date', null, ['class' => 'form-control  ', 'id' => 'payment_date', 'autocomplete' => 'off','required','data-focus'=>"false"]) }}
                    </div>
                    <div class="form-group col-lg-4 col-sm-12 mb-5 ">
                        {{ Form::label('amount',__('messages.invoice.amount').':', ['class' => 'form-label required mb-3']) }}
                        <div class="input-group">
                            {{ Form::number('amount', null, ['id'=>'amount','class' => 'form-control  amount d-flex','step'=>'any','oninput'=>"validity.valid||(value=value.replace(/[e\+\-]/gi,''))",'min'=>'0','pattern'=>"^\d*(\.\d{0,2})?$",'required','placeholder'=>__('messages.invoice.amount')]) }}
                            <a class="input-group-text bg-secondary border-0 text-decoration-none invoice-currency-code" id="autoCode" href="javascript:void(0)"
                               data-toggle="tooltip"
                               data-placement="right" title="Currency Code">
                                {{ getCurrencySymbol() }}
                            </a>
                        </div>
                    </div>
                  <div class="form-group col-lg-4 col-sm-12 mb-5">
                    {{ Form::label('payment_mode', __('messages.payment.payment_mode') . ':', ['class' => 'form-label required mb-3']) }}
                    <div class="input-group">
                         {{ Form::select('payment_mode', $paymentMethods, null,['id'=>'payment_mode','class' => 'form-select','required','data-control' =>'select2']) }}
                    </div>
                </div>
                    <div class="form-group col-sm-12 mb-5">
                        {{ Form::label('notes',__('messages.invoice.note').':', ['class' => 'form-label required mb-3']) }}
                        {{ Form::textarea('notes', null, ['id'=>'payment_note','class' => 'form-control ','rows'=>'5','required']) }}
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.pay'), ['type' => 'submit','class' => 'btn btn-primary me-2','id' => 'btnPay','data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing...", 'data-new-text' => __('messages.common.pay')]) }}
                <button type="button" class="btn btn-secondary btn-active-light-primary"
                        data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
