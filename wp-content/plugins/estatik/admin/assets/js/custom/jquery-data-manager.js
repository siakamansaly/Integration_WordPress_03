jQuery.fn.dataManagerItem = function(options) {
    var $ = jQuery || $;
    var $el = $(this);
    var $form = $el.find('form');
    var $msg = $el.find('.es-data-manager-item-msg');
    var $ul = $el.find('ul');
    var container = $form.data('container');
    var storage = $form.data('storage');
    var removeAction = $form.data('remove-action');

    $form.find('.es-data-manager-submit').on('click', function() {
        $form.submit();
        return false;
    });

    $form.submit(function() {
        var formData = $form.serialize();
        var itemName = $form.find('input[type=text]').val();

        $.post(ajaxurl, formData, function(response) {
            response = JSON.parse(response);
            if (response.status) {
                $msg.removeClass('success', 'warning', 'error').addClass(response.status).html(response.message);

                if (response.status === 'success' && response.item) {
                    if (response.content) {
                        $ul.append(response.content);
                    } else {
                        var radio = container !== undefined ? '<input type="radio" class="js-item-radio" data-action="es_ajax_data_manager_check_option" ' +
                            'name="id" value="' + response.item + '">' : '';
                        $ul.append('<li>' + radio + itemName +
                            ' <a href="#" class="es-item-remove js-item-remove" data-storage="' + storage + '" data-container="' + container + '" data-action="' + removeAction + '" data-id="' + response.item + '"><span class="es-sprite es-sprite-close"></span></a></li>')
                    }
                }
            }
        }).fail(function() {
            $msg.removeClass('success', 'warning', 'error').addClass('error').html('Item doesn\'t create. Please contact support.');
        });

        $form.find('[type=text]').val('');

        return false;
    });

    $el.on('click', '.js-item-remove', function a() {
        var $linkEl = $(this);

        $('.es-confirm-popup').esPopup('yesCallback', function() {

            var id = $linkEl.data('id');
            var action = $linkEl.data('action');
            var $link = $linkEl;

            if ( id !== undefined ) {
                $.post( ajaxurl, { nonce: Estatik.settings.admin_nonce, id: id, action: action, container: container, storage: storage }, function( response ) {
                    response = JSON.parse( response );

                    if ( response.status ) {
                        $msg.removeClass('success', 'warning', 'error').addClass( response.status ).html( response.message );
                    }

                    if ( response.status === 'success' ) {
                        $link.closest('li').remove();
                    }
                } ).fail(function() {
                    $msg.removeClass('success', 'warning', 'error').addClass('error').html('Item doesn\'t remove. Please contact support.');
                } );
            }

            $(this).esPopup('hide');

            return false;
        }).esPopup('show');

        return false;
    });

    $el.on('click', '.js-item-radio', function() {
        var action = $(this).data('action');

        $.post(ajaxurl, {nonce: Estatik.settings.admin_nonce, checked: $(this).is(':checked'), id: $(this).val(), action: action, container: container, storage: storage}, function(response) {
            response = JSON.parse(response);

            if (response.status) {
                $msg.removeClass('success', 'warning', 'error').addClass(response.status).html(response.message);
            }
        }).fail(function() {
            $msg.removeClass('success', 'warning', 'error').addClass('error').html('Item doesn\'t remove. Please contact support.');
        });
    });

    $el.on('change', '.js-color-item', function() {
        var action = $(this).data('action');

        $.post(ajaxurl, {nonce: Estatik.settings.admin_nonce, id: $(this).data('id'), color: $(this).val(), action: action, container: container, storage: storage}, function(response) {
            response = JSON.parse(response);

            if (response.status) {
                $msg.removeClass('success', 'warning', 'error').addClass(response.status).html(response.message);
            }
        }).fail(function() {
            $msg.removeClass('success', 'warning', 'error').addClass('error').html('Item doesn\'t changed. Please contact support.');
        });
    });
};
