@php
    use App\Models\AdminPayment;
    
    // Fetch all admin payments
    // Fetch all admin payments
    $adminPayments = AdminPayment::all();

    // Extract unique client names and their corresponding invoice IDs from admin payments
    $uniqueClientDetails = $adminPayments->groupBy('invoice.client.user.full_name')->map(function ($group) {
        return $group->first();
    });
@endphp
<div class="client-name-input me-5">
        <select id="clientNameInput" class="form-select select2">
            <option value="">Select Client Name</option>
            @foreach ($uniqueClientDetails as $details)
                <option value="{{ $details->invoice->client_id }}">{{ $details->invoice->client->user->full_name }}</option>
            @endforeach
        </select>
</div>
<div class="my-3 my-sm-3" wire:ignore>
    <div class="date-ranger-picker me-2">
        <input type="text" id="paymentDateFilter" class="form-control" readonly placeholder="YYYY-MM-DD - YYYY-MM-DD">
    </div>
</div>
<div class="dropdown my-3 my-sm-3 me-2">
    <button class="btn btn-success text-white dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('messages.common.export') }}
    </button>
    <ul class="dropdown-menu export-dropdown">
        <a id="adminPaymentExcelExport" data-turbo="false" type="button" class="btn btn-outline-success dropdown-item">
            <i class="fas fa-file-excel me-1"></i> {{ __('messages.invoice.excel_export') }}
        </a>
        <a id="adminPaymentPdfExport" type="button" class="btn btn-outline-info me-2 dropdown-item" data-turbo="false">
            <i class="fas fa-file-pdf me-1"></i> {{ __('messages.pdf_export') }}
        </a>
    </ul>
</div>
<div>
    <button type="button" class="btn btn-primary addPayment">
        {{ __('messages.payment.add_payment') }}
    </button>
</div>

<script>
    $(document).ready(function () {
        $('#adminPaymentExcelExport').on('click', function () {
            var paymentDateFilter = $('#paymentDateFilter').val().split(' - ');
            var clientNameFilter = $('#clientNameInput').val();

            var exportUrl = "{{ route('adminpayments.excel') }}";  // Corrected route name

            window.location.href = `${exportUrl}?paymentDateFilter[]=${paymentDateFilter[0]}&paymentDateFilter[]=${paymentDateFilter[1]}&clientNameFilter=${clientNameFilter}&exportType=excel`;
        });

        $('#adminPaymentPdfExport').on('click', function () {
            var paymentDateFilter = $('#paymentDateFilter').val().split(' - ');
            var clientNameFilter = $('#clientNameInput').val();

            var exportUrl = "{{ route('adminpayments.pdf') }}";  // Corrected route name

            window.location.href = `${exportUrl}?paymentDateFilter[]=${paymentDateFilter[0]}&paymentDateFilter[]=${paymentDateFilter[1]}&clientNameFilter=${clientNameFilter}&exportType=pdf`;
        });
    });
</script>
