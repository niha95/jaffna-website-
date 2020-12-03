<script>
    window.translations = window.translations instanceof  Object
            ? $.extend(window.tranlations, JSON.parse($('#Translations').val()))
            : JSON.parse($('#Translations').val());

    window.urls = window.urls instanceof  Object
            ? $.extend(window.urls, JSON.parse($('#Urls').val()))
            : JSON.parse($('#Urls').val());
</script>