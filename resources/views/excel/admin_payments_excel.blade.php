<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payments Excel</title>
</head>

<body>
    <table class="table table-bordered border-primary">
        <thead>
        <tr>
            <th style="width: 100px"><b>Invoice Date</b></th>
            <th style="width: 100px"><b>Invoice No</b></th>
            <th style="word-break: break-all; width: 300px"><b>Customer Name</b></th>
            <th style="width: 100px"><b>Paid Amount</b></th>
            <th style="width: 200px"><b>Payment Received Date</b></th>
            <th style="width: 100px"><b>Invoice Amount</b></th>
            <th style="width: 200px"><b>Payment Method</b></th>
            <th style="width: 300px"><b>Notes</b></th>
        </tr>
        </thead>
        <tbody>
        @if(count($adminPayments) > 0)
            @foreach($adminPayments as $payment)
                <tr class="custom-font-size-pdf">
                    <td>{{ \Carbon\Carbon::parse($payment->invoice->invoice_date)->format('Y-m-d') }}</td>
                    <td>{{ $payment->invoice->invoice_id }}</td>
                    <td>{{ $payment->invoice->client->user->full_name }}</td>
                    <td style="text-align: right" data-value="{{ $payment->amount }}">
                        {{ $payment->amount }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}</td>
                    <td style="text-align: right" data-value="{{ $payment->invoice->final_amount }}">
                        {{ $payment->invoice->final_amount }}
                    </td>
                    <td>{{ $payment->payment_mode }}</td>
                    <td>{{ $payment->notes }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="8" class="text-center">{{ __('messages.no_records_found') }}</td>
            </tr>
        @endif
        </tbody>
    </table>
</body>

</html>
