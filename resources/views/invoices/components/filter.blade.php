<div wire:ignore id="date-ranger-picker">
    <input id="daterange" name="daterange" class="form-control text-center removeFocus"
        placeholder="MM/DD/YYYY - MM/DD/YYYY" wire:model="dateRange">
</div>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    // Initialize DateRangePicker with improved options
    $('#date-ranger-picker').daterangepicker({
        opens: 'right',
        autoUpdateInput: false,
        showDropdowns: true,
        alwaysShowCalendars: true,
        showCustomRangeLabel: false,
        linkedCalendars: false,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                'month').endOf('month')]
        },
        locale: {
            cancelLabel: 'Clear',
            applyLabel: 'Apply',
            format: 'MM/DD/YYYY',
            separator: ' - ',
        },
        buttonClasses: 'btn',
        applyButtonClasses: 'btn-primary',
        cancelButtonClasses: 'btn-default'
    });

    $('#date-ranger-picker').on('apply.daterangepicker', function(ev, picker) {
        let dateRange = picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD');
        $('#daterange').val(dateRange);
        Livewire.dispatch('dateRangeUpdated', { dateRange: dateRange });
    });

    $('#date-ranger-picker').on('cancel.daterangepicker', function(ev, picker) {
        $('#daterange').val('');
        Livewire.dispatch('dateRangeUpdated', { dateRange: '' });
    });
</script>

<div wire:ignore>
    <div class="ms-0 ms-md-2">
        <div class="dropdown d-flex align-items-center me-4 me-md-2" wire:ignore>
            <button class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0" type="button"
                id="invoiceFilterBtn" data-bs-auto-close="outside" data-bs-toggle="dropdown" aria-expanded="false">
                <p class="text-center">
                    <i class='fas fa-filter'></i>
                </p>
            </button>
            <div class="dropdown-menu py-0" aria-labelledby="invoiceFilterBtn">
                <div class="text-start border-bottom py-4 px-7">
                    <h3 class="text-gray-900 mb-0">{{ __('messages.common.filter_options') }}</h3>
                </div>
                <div class="p-5">
                    <div class="mb-5">
                        <label for="filterBtn" class="form-label">{{ __('messages.common.status') }}:</label>
                        {{ Form::select(
                            'status',
                            collect($filterHeads[0])->sortBy('key')->toArray(),
                            \App\Models\Invoice::STATUS_ALL,
                            ['class' => 'form-control form-control-solid form-select', 'data-control' => 'select2', 'id' => 'invoiceStatus'],
                        ) }}
                    </div>
                    <div class="mb-5">
                        <label for="filterBtn"
                            class="form-label">{{ __('messages.invoice.recurring') . ' ' . __('messages.common.status') }}
                            :</label>
                        {{ Form::select(
                            'recurring_status',
                            collect($filterHeads[1])->sortBy('key')->reverse()->toArray(),
                            null,
                            [
                                'class' => 'form-control form-control-solid form-select',
                                'data-control' => 'select2',
                                'id' => 'recurringStatus',
                                'placeholder' => 'All',
                            ],
                        ) }}
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-secondary"
                            id="resetInvoiceFilter">{{ __('messages.common.reset') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
