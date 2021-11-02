(function($) {
    'use strict';

    var $progressContainer;
    var $form;
    var $loggerContainer;

    function esMigrate(data) {
        $.ajax({
            url: Estatik.ajaxurl,
            data: data,
            type: "POST",
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            success: function(response) {
                response = response || {};

                var formData = new FormData();

                for (var i in response) {
                    formData.append(i, response[i])
                }

                if (response.progress) {
                    $progressContainer.show().percent(response.progress);
                }

                if (response.messages && response.messages) {
                    for (var type in response.messages) {
                        if (response.messages[type] && response.messages[type][response.index] != 'undefined') {
                            for (var j in response.messages[type]) {
                                $loggerContainer.append('<li class="es-message es-message-' + type + '">' + response.messages[type][j] + '</li>')
                            }
                        }
                    }
                }

                $('.es-scroll-container').mCustomScrollbar().mCustomScrollbar('scrollTo', 'bottom');

                if (!response.done) {
                    esMigrate(formData);
                } else {
                    $form.find('input[type=submit]').removeProp('disabled');

                    setTimeout(function() {
                        window.location.href = Estatik.settings.listingsLink;
                    }, 1500);
                }
            },
            dataType: 'json'
        });
    }

    $(function() {
        $progressContainer = $('.es-progress');
        $form = $('#es-migrate-form');
        $loggerContainer = $('.es-logger-container');
        var $msg = $('.es-msg-1');
        var $msg2 = $('.es-msg-2');

        $progressContainer.Progress({
            width: $progressContainer.parent().width(),
            height: 31,
            percent: 0,
            backgroundColor: '#d1d1d1',
            barColor: '#82C728',
            fontColor: '#fff',
            radius: 2,
            fontSize: 12,
            increaseTime: 0.0001,
            increaseSpeed: 3,
            animate: true
        }).hide();

        $form.on('submit', function() {
            var form = document.getElementById(this.id);
            var formData = new FormData(form);

            $(this).find('input[type=submit]').prop('disabled', true);

            esMigrate(formData);

            $msg.addClass('es-hidden');
            $msg2.removeClass('es-hidden');

            return false;
        });
    });
})(jQuery);
