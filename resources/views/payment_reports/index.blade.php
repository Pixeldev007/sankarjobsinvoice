@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Payment Reports</h3>

        <form action="{{ route('payment.reports.show') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>

                @php
                    use App\Models\AdminPayment;
                    $adminPayments = AdminPayment::with('invoice.client.user')->get();
                    $uniqueClientDetails = $adminPayments
                        ->groupBy('invoice.client.user.full_name')
                        ->map(function ($group) {
                            return $group->first();
                        });
                @endphp

                <!-- Client Name Dropdown -->
                <div class="form-group col-md-3 mb-3">
                    <div class="input-group">
                        {{ Form::select(
                            'client_id',
                            $uniqueClientDetails->pluck('invoice.client.user.full_name', 'invoice.client_id'),
                            null,
                            [
                                'id' => 'clientNameInput',
                                'class' => 'form-select select2',
                                'style' => 'width: 100%;',
                                'placeholder' => 'Select Client Name',
                                'data-control' => 'select2',
                            ],
                        ) }}
                    </div>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>

                <div class="col-md-2">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Export
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('payment.reports.pdf') }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&client_id={{ request('client_id') }}">
                                    Export PDF
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('payment.reports.excel') }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&client_id={{ request('client_id') }}">
                                    Export Excel
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>

        @if (isset($payments) && $payments->isNotEmpty())
            <table id="paymentReportTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Payment Date</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                        <tr>
                            <td>{{ $payment->invoice->client->user->full_name }}</td>
                            <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                            <td>{{ getInvoiceCurrencyAmount($payment->amount, $payment->invoice->currency_id, true) }}</td>
                            <td>{{ $payment->payment_mode }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No payments found for the selected date range.</p>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#paymentReportTable').DataTable({
                dom: 'Bfrtip',
                paging: false,
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

            // Initialize Select2 for client name dropdown
            $('#clientNameInput').select2({
                placeholder: "Select a client",
                allowClear: true
            });
        });
    </script>
@endpush
