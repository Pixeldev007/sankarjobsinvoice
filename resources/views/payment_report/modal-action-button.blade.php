<div class="d-flex justify-content-center">
    @if (isset($value['show-route']))
        <a href="javascript:void(0)" data-id="{{ $dataId }}" title="Show" data-bs-toggle="tooltip"
            class="{{ $value['data-show-id'] }} btn px-2 text-info fs-3 py-2" data-bs-toggle="tooltip">
            <i class="fas fa-eye"></i>
        </a>
    @endif
<!-- resources/views/livewire/modal-action-button.blade.php -->
<div class="width-80px text-center">
    <a href="javascript:void(0)" wire:click="edit({{ $dataId }})"
       class="btn px-2 text-primary fs-3 py-2 {{ $editClass }}"
       data-bs-toggle="tooltip" title="{{ __('messages.common.edit') }}">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    
</div>



</div>
