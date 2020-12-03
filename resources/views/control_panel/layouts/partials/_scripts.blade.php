<!-- Main Scripts -->
<script src="{{ asset('assets/control-panel/js/jquery-1.11.3.min.js') }}"></script>
<script src="{{ asset('assets/control-panel/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/control-panel/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/control-panel/js/lightbox.min.js') }}"></script>
<script src="{{ asset('assets/control-panel/js/main.js') }}"></script>

<script>
    $('#LogoutLink').on('click', function (e) {
        e.preventDefault();

        $('#LogoutForm').submit();
    });

    $('#Sections').on('click', '.section_collapse', function () {
        if ($(this).text() == "&minus;") {
            alert('here')
        }
    });

    function deleteImage(element) {
        var element = $(element);

        element.siblings('.ajax_loader').show();

        $.ajax({
            url: element.attr('data-delete-url'),
            method: 'DELETE',
            data: {'_token': element.attr('data-csrf-token')},
            success: function () {
                element.parent('.thumb_widget').fadeOut();
            }
        });
    }

    function editImage(element){
        var element = $(element);

        $.ajax({
            url: element.attr('data-edit-url'),
            method: 'GET',
            success: function (response) {
                var modal = $('#GeneralModal');

                modal.find('.modal-title').text(response.title);
                modal.find('.modal-body').html(response.content);

                modal.modal('show');
            }
        });
    }

    function updateImage(){
        var form = $('#EditImageForm');

        console.log(form.attr('action'));

        $.ajax({
            url: form.attr('action'),
            method: 'patch',
            data: form.serialize(),
            beforeSend: function(){
                form.find('button[type=submit]')
                        .append('<img class="loading_image_sm" src="/assets/control-panel/images/loading.gif">');
            },
            success: function(){
                $('#GeneralModal').modal('hide');
            }
        });

        return false;
    }

    function confirmDeleteEntity(element){
        confirmation_modal.element = $(element);

        $(confirmation_modal).off('operation_confirmed');

        $(confirmation_modal).on('operation_confirmed', function(){
            deleteEntity();
        });

        confirmation_modal.show();
    }

    function deleteEntity() {
        var element = confirmation_modal.element;

        $.ajax({
            url: element.attr('data-url'),
            method: 'POST',
            data: {'_token': '{{ csrf_token() }}', '_method': 'DELETE'},
            success: function (response) {
                if (response.status == 'success') {
                    element.parents('tr').fadeOut()
                    toastr.success(response.message);
                    element.trigger('entity_delete');
                } else {
                    toastr.error(response.message);
                }

                confirmation_modal.hide();
            }
        });
    }

    $(document).ready(function(){
        $('.reorder_button').on('click', function(e){
            e.preventDefault();

            var elem = $(this);
            var token = $('meta[name=_token]').attr('content');

            $.form(elem.data('url'), {_token: token}, 'POST').submit();
        });
    });

    function toggleSlug(element) {
        if ($(element).prop('checked')) {
            $('#Slug').removeProp('readonly');
        } else {
            $('#Slug').attr('readonly', 'readonly');
        }
    }

    function slugify(value) {
        return value.toLowerCase()
                .replace(/[^\w \u0600-\u06FF \-]+/g, '')
                .replace(/ +/g, '-')
                .replace(/\-+/g, '-');
    }

    $('#Slug').on('keypress change', function () {
        var slug = slugify($(this).val());

        $(this).val(slug);
    });

    $('[data-sluggable]').on('keyup keydown', function () {
        var slug_field = $('#Slug');

        if (slug_field.prop('readonly')) {
            var slug = slugify($(this).val());

            slug_field.val(slug);
        }
    });
</script>

{{-- Custom JS --}}
@yield('js')

@stack('js')