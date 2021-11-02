(function($) {

    var file_frame;

    $(document).on('click', '#es-media a.gallery-add', function(e) {

        e.preventDefault();

        if (file_frame) file_frame.close();

        file_frame = wp.media.frames.file_frame = wp.media({
            title: $(this).data('uploader-title'),
            button: {
                text: $(this).data('uploader-button-text')
            },
            multiple: true
        });

        file_frame.on('select', function() {
            var listIndex = $('#es-media-list li').index($('#es-media-list li:last')),
                selection = file_frame.state().get('selection');

            selection.map(function(attachment, i) {
                attachment = attachment.toJSON();
                var index  = listIndex + (i + 1);
                var src = typeof attachment.sizes.thumbnail !== 'undefined' ? attachment.sizes.thumbnail.url : attachment.sizes.full.url;

                $('#es-media-list').append('<li>' +
                    '<input type="hidden" name="property[gallery][' + index + ']" value="' + attachment.id + '">' +
                    '<div class="image-preview-wrap">' +
                    '<a class="remove-image" href="#"><i class="fa fa-times-circle" aria-hidden="true"></i></a>' +
                    '<a href="#" class="drag-image"><i class="fa fa-arrows" aria-hidden="true"></i></a>' +
                    '<img class="image-preview" src="' + src + '">');
            });
        });

        makeSortable();

        file_frame.open();

    });

    function resetIndex() {
        $('#es-media-list li').each(function(i) {
            $(this).find('input:hidden').attr('name', 'property[gallery][' + i + ']');
        });
    }

    function makeSortable() {
        var $list = $('#es-media-list');

        if ($list.length)
            $list.sortable({
                opacity: 0.6,
                stop: function() {
                    resetIndex();
            }
        });
    }

    $(document).on('click', '#es-media-list a.remove-image', function(e) {
        e.preventDefault();

        $(this).parents('li').animate({ opacity: 0 }, 200, function() {
            $(this).remove();
            resetIndex();
        });
    });

    makeSortable();

})(jQuery);
