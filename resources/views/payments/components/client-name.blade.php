<div class="d-flex align-items-center">
    <div class="d-flex flex-column">
        <div class="row">
            <div class="col-lg-12">
                <a href="{{route('clients.show',$row->invoice->client->id)}}"
                   class="mb-1 text-info text-decoration-none">{{$row->invoice->client->user->full_name}}</a>
                <a href="{{route('invoices.show',$row->invoice->id)}}"
                   class=" badge bg-light-primary text-decoration-none mb-1">{{$row->invoice->invoice_id}}</a>
            </div>
        </div>
          </div>
</div>
