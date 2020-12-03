<div class="section">
    <div data-action="{{ route('control_panel.images.dropzone_uploader') }}"
         class="dropzone text-center"
         id="DropzoneUploader"
         data-album-id="{{ $album->id }}"
         data-csrf-token="{{ csrf_token() }}"></div>
</div>

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/control-panel/css/dropzone.min.css') }}">
@append

@section('js')
    <script src="{{ asset('assets/control-panel/js/dropzone.min.js') }}"></script>
    <script>
        Dropzone.autoDiscover = false;

        $('#DropzoneUploader').dropzone({
            url: $('#DropzoneUploader').attr('data-action'),
            paramName: 'uploaded_image',
            maxFilesize: 2,
            acceptedFiles: 'image/*',
            init: function () {
                var total_uploaded_files = 0;

                this.on('success', function () {
                    total_uploaded_files += 1;
                })

                this.on('queuecomplete', function () {
                    if (total_uploaded_files > 0) {
                        location.reload();
                    }
                });

                this.on('sending', function (file, xhr, formData) {
                    var element = $('#DropzoneUploader');

                    formData.append('album_id', element.attr('data-album-id'));
                    formData.append('_token', element.attr('data-csrf-token'));
                });
            }

        });
    </script>
@append