<div class="d-flex align-items-center">
    <div class="d-flex flex-column">
        <div class="row">
            <div class="col-lg-12">
                <a href="{{route('clients.show', $row->client->id)}}"
                   class="mb-1 text-primary text-decoration-none">{{$row->client->user->full_name}}</a>&nbsp;
                <a href="{{route('invoices.show', $row->id)}}"
                   class="badge bg-light-info text-decoration-none">{{$row->invoice_id}}</a>
                @if($row->recurring_status)
                    <span class="text-primary recurring-cycle-icon" data-bs-toggle="tooltip" data-placement="right"
                          title="Recurring Invoice is On">
                        <i class="fas fa-recycle"></i>
                    </span>
                @endif
            </div>
        </div>
          </div>
</div>


