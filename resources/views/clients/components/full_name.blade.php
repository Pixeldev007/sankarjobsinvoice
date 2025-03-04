@if(!empty($row->user->full_name))
<div class="d-flex align-items-center">
    <div class="d-flex flex-column">
        <a href="{{ route('clients.show', $row->id) }}" class="mb-1 text-decoration-none">{{ $row->user->full_name }}</a>
    </div>
</div>
@endif
