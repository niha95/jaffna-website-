@if(isset($moveUpUrl))
    <button type="button" class="btn btn-primary reorder_button"
            data-url="{{ $moveUpUrl }}"
    >
        <i class="fa fa-level-up" aria-hidden="true"></i>
    </button>
@endif

@if(isset($moveDownUrl))
    <button type="button" class="btn btn-primary reorder_button"
            data-url="{{ $moveDownUrl }}"
    >
        <i class="fa fa-level-down" aria-hidden="true"></i>
    </button>
@endif