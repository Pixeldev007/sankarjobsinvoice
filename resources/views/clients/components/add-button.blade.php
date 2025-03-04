<div class="dropdown my-3 my-sm-3 me-2">
    <button class="btn btn-success text-white dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('messages.common.export') }}
    </button>
    <ul class="dropdown-menu export-dropdown">
        <a href="/clients-exel" data-turbo="false" type="button" class="btn btn-outline-success dropdown-item">
            <i class="fas fa-file-excel me-1"></i> {{ __('messages.invoice.excel_export') }}
        </a>
        <a href="/clients-pdf" type="button" class="btn btn-outline-info me-2 dropdown-item" data-turbo="false">
            <i class="fas fa-file-pdf me-1"></i> {{ __('messages.pdf_export') }}
        </a>
    </ul>
</div>
<a type="button" class="btn btn-primary" href="{{ route('clients.create')}}">
    {{__('messages.client.add_client')}}
</a>
