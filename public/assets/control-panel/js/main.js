Array.prototype.move = function (old_index, new_index) {
    if (new_index >= this.length) {
        var k = new_index - this.length;
        while ((k--) + 1) {
            this.push(undefined);
        }
    }
    this.splice(new_index, 0, this.splice(old_index, 1)[0]);
};

Object.defineProperty(Array.prototype, 'chunk', {
    value: function(chunkSize) {
        var R = [];
        for (var i=0; i<this.length; i+=chunkSize)
            R.push(this.slice(i,i+chunkSize));
        return R;
    }
});


function RichEditor(id, opts){

    this.el = $('#' + id);
    this.options = (opts !== undefined) ? opts : {};

    var editor = CKEDITOR.replace(id, {
        language: this.options.locale
    });

    if(this.options.imagesManager  == true) {
        editor.addCommand('openImageGallery', {
            exec: function(){
                manager.show()
            }
        });

        editor.ui.addButton('images_gallery', {
            label: 'Images Manager',
            command: 'openImageGallery',
            toolbar: 'insert'
        });
    }
}

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

$('#Slug').on('keydown', function () {
    var slug = slugify($(this).val());

    $(this).val(slug);
});

$('[data-sluggable]').on('keydown keyup', function () {
    var slug_field = $('#Slug');

    if (slug_field.prop('readonly')) {
        var slug = slugify($(this).val());

        slug_field.val(slug);
    }
});