@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Invoice Reports</h3>

        <form action="{{ route('invoice.reports.show') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>

                @php
                    use App\Models\AdminPayment;

                    // Fetch all admin payment
                    $adminPayments = AdminPayment::all();

                    // Extract unique client names and their corresponding invoice IDs from admin payments
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


                <!-- Filter Button -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>

                <!-- Export Dropdown -->
                <div class="col-md-2">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Export
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('invoice.reports.pdf') }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&client_id={{ request('client_id') }}">
                                    Export PDF
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('invoice.reports.excel') }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&client_id={{ request('client_id') }}">
                                    Export Excel
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>

        <!-- Invoice Table -->
        @if (isset($invoices) && $invoices->isNotEmpty())
            <table id="invoiceReportTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Invoice ID</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_id }}</td>
                            <td>{{ $invoice->client->user->FullName }}</td>
                            <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                            <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
                            <td>{{ $invoice->final_amount }}</td>
                            <td>
                                @if ($invoice->status_label === 'Paid')
                                    <span class="badge bg-light-success fs-7">{{ $invoice->status_label }}</span>
                                @elseif($invoice->status_label === 'Unpaid')
                                    <span class="badge bg-light-danger fs-7">{{ $invoice->status_label }}</span>
                                @elseif($invoice->status_label === 'Partially Paid')
                                    <span class="badge bg-light-primary fs-7">{{ $invoice->status_label }}</span>
                                @elseif($invoice->status_label === 'Draft')
                                    <span class="badge bg-light-warning fs-7">{{ $invoice->status_label }}</span>
                                @else
                                    <span class="badge bg-light-danger fs-7">{{ $invoice->status_label }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No invoices found for the selected date range.</p>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#invoiceReportTable').DataTable({
                dom: 'Bfrtip',
                paging: false, // Disable DataTable pagination
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
