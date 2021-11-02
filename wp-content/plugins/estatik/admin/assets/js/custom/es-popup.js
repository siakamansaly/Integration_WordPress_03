jQuery.fn.esPopup = function(options, v) {
    var $ = jQuery || $;
    options = options || {};

    var $el = $(this);

    if (typeof options === 'string' || options instanceof String) {

        switch (options) {
            case 'show':
                showPopup();
                break;

            case 'hide':
                closePopup();
                break;

            case 'yesCallback':
                var wrap = $el.closest('.es-popup-wrap');
                wrap.off('click', '.es-popup-yes');
                wrap.on('click', '.es-popup-yes', v);
                break;

            case 'message':
                $el.html(v);

                break;
        }

    } else {
        var confirmPopup = options.confirmPopup || false;
        var message = options.message || '';
        var yesButtonLabel = options.yesButtonLabel || Estatik.tr.yes;
        var noButtonLabel = options.noButtonLabel || Estatik.tr.no;
        var okButtonLabel = options.okButtonLabel || Estatik.tr.ok;
        var $triggerElement = options.triggerElement;
        var yesCallback = options.yesCallback || null;
        var noCallback = options.noCallback || null;
        var okCallback = options.okCallback || closePopup;

        buildPopup();
    }

    $el.closest('.es-popup-wrap').on('click', '.es-popup-close', function() {
        closePopup();

        return false;
    });

    $el.closest('.es-popup-wrap').on('click', '.es-popup-no', function() {
        closePopup();

        return false;
    });

    function buildPopup() {
        if (!$el.closest('.es-popup-wrap').length) {
            $el.wrap('<div class="es-popup-wrap"><div class="es-popup-content"></div></div>');
            $el.closest('.es-popup-wrap').append('<a href="#" class="es-popup-close"><i class="fa fa-times-circle" aria-hidden="true"></i></a>');

            if (confirmPopup) {
                $el.closest('.es-popup-content').append('<div class="es-popup-buttons">' +
                    '<a href="" class="es-button es-button-gray es-popup-yes">' + yesButtonLabel + '</a> ' +
                    '<a href="" class="es-button es-button-gray es-popup-no">' + noButtonLabel + '</a> ' +
                    '</div>');
            } else {
                $el.closest('.es-popup-content').append('<div class="es-popup-buttons">' +
                    '<a href="" class="es-button es-button-gray es-popup-no">' + okButtonLabel + '</a> ' +
                    '</div>');
            }
        }

        if (!$el.closest('body').find('.es-popup-background').length) {
            $el.closest('body').append('<div class="es-popup-background"></div>');
        }
    }

    function showPopup() {
        $el.closest('.es-popup-wrap').fadeIn(500);
        $('.es-popup-background').fadeIn(500);
    }

    function closePopup() {
        $el.closest('.es-popup-wrap').fadeOut(500);
        $('.es-popup-background').fadeOut(500);
    }

    return $el;
};
