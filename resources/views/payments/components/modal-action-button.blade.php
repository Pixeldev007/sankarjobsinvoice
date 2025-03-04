<div class="dropup position-static" wire:key="{{ $row->id }}">
    <a class="px-5" href="{{ route('paymentbill.pdf', $row->id) }}">
        <i class="fa-solid fa-download fs-2"></i>
    </a>
</div>