<div class="thumb_widget img-thumbnail">

    <a href="javascript:void(0)" class="close"
       onclick="deleteImage(this)"
       data-delete-url="{{ route('control_panel.productalbum.destroy', $image->id) }}"
       data-csrf-token="{{ csrf_token() }}"
    >
        <span aria-hidden="true">&times;</span>
    </a>

    <img src="{{ $image->thumbFullLink() }}"
         alt="{{ $alt_text or $image->caption_translated_piped }}"
         title="{{ $image->caption_translated_piped }}"
    >

    <div class="edit_button_wrapper">
        <div class="edit_button_inner_wrapper">
            <button type="button"
                    class="btn btn-default"
                    data-edit-url="{{ route('control_panel.images.edit', $image->id) }}"
                    onclick="editImage(this)"
            >
                <i class="fa fa-pencil" aria-hidden="true"></i>
            </button>
            <a href="{{ $image->imageFullLink() }}" class="btn btn-default"
               data-lightbox="{{ isset($album) ? $album->name_translated_piped : $image->id }}"
               data-title="{{ $image->caption_translated_piped }}"
            >
                <i class="fa fa-expand" aria-hidden="true"></i>
            </a>
        </div>
    </div>

    <img src="{{ asset('assets/control-panel/images/loading.gif') }}" class="ajax_loader">
</div>