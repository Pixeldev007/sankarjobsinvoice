<!-- resources/views/transactions/components/payment-mode.blade.php -->
<div>
    @if(isset($row->payment_mode))
        {{ $row->payment_mode }}
    @endif
</div>