<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/media/logos/favicon.ico') }}" type="image/png">
    <title>Payments PDF</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <!-- General CSS Files -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/invoice-pdf.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .custom-font-size-pdf {
            font-size: 11px !important;
        }

        .table thead th {
            font-size: 12px !important;
        }
    </style>
</head>
<body>
<div class="d-flex align-items-center justify-content-center mb-4">
    <h4 class="text-center">Payments Export Data</h4>
</div>
<table class="table table-bordered border-primary">
    <thead>
    <tr>
        <th style="width: 18%"><b>Invoice Date</b></th>
        <th style="width: 18%"><b>Invoice No</b></th>
        <th style="word-break: break-all;width: 20%"><b>Customer Name</b></th>
        <th style="width: 12%"><b>Paid Amount</b></th>
        <th style="width: 18%"><b>Payment Received Date</b></th>
        <th style="width: 12%"><b>Invoice Amount</b></th>
        <th style="width: 15%"><b>Payment Method</b></th>
        <th style="width: 30%"><b>Notes</b></th>
    </tr>
    </thead>
    <tbody>
       <tr class="custom-font-size-pdf">
                <td>{{ $adminPayments->invoice->invoice_date }}</td>
                <td>{{ $adminPayments->invoice->invoice_id }}</td>
                <td>{{ $adminPayments->invoice->client->user->full_name }}</td>
                <td style="text-align: right">{{ getInvoiceCurrencyAmount($adminPayments->amount, $adminPayments->invoice->currency_id, true) }}</td>
                <td>{{ Carbon\Carbon::parse($adminPayments->payment_date)->format(currentDateFormat()) }}</td>
                <td>{{ $adminPayments->invoice->final_amount }}</td>
                <td>{{ $adminPayments->payment_mode }}</td>
                <td>{{ $adminPayments->notes }}</td>
            </tr>
    </tbody>
</table>

</body>
</html>
