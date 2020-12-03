@section('js')
    <script src="{{ asset('assets/control-panel/js/ckeditor/ckeditor.js') }}"></script>
    <script>
        var selected_editor = '';

        var fields = $('.ck_editor');
        var images_manager = '{{ $images_manager or 0 }}';

        if(images_manager != 0) {
            manager.setMode('images');
            $(manager).off('im.image_selected');
            $(manager).on('im.image_selected', function(e, image){
                CKEDITOR.instances[selected_editor].insertHtml('<img src="' + image.image_url + '" caption="' + image.caption +'">');
            });
        }

        CKEDITOR.on('instanceReady', function(evt) {
            var ed = evt.editor;

            ed.on('focus', function(e) {
                selected_editor = e.editor.name;
            });
        });

        $.each(fields, function(){
            new RichEditor($(this).attr('id'), {imagesManager: images_manager});
        });
    </script>
@append