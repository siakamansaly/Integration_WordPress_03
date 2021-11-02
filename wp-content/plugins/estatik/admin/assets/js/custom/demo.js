( function( $ ) {
    'use strict';

    var $progressContainer;
    var $loggerContainer;

    function toTop() {
        $('html,body').animate({
            scrollTop: 0
        }, 700);
    }

    function startSetup( data ) {
        $.post(ajaxurl, data, function( response ) {
            response = response || {};

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

            $('.es-scroll-container').mCustomScrollbar().mCustomScrollbar( 'scrollTo', 'bottom' );

            if (!response.done) {
                startSetup( response );
            } else {
                setTimeout(function() {
                    window.location.href = Estatik.settings.demoFinished;
                }, 1000);
            }
        }, 'json' );
    }

    $( function() {
        var $pagesList = $( '.js-pages-list' );
        var $switchStepLink = $( '.js-switch-step' );
        var $setupLink = $( '.js-setup-btn' );
        var $importDemoLink = $( '.js-import-demo' );
        $loggerContainer = $( '.es-logger-container' );

        $importDemoLink.on( 'click', function() {

            $( this ).hide().closest( '.es-step' ).find( '.es-demo__navigation' ).hide();

            $( '.js-demo-field' ).val(1);

            startSetup( $( this ).closest( 'form' ).serialize() );

            return false;
        } );

        $progressContainer = $('.es-progress');

        $setupLink.on( 'click', function() {
            $( this ).closest( 'form' ).submit();

            return false;
        } );

        $pagesList.find( 'li' ).on( 'click', function() {
            var $el = $( this );
            var $checkbox = $el.find( '[type=checkbox]' );

            if ( ! $el.hasClass( 'disabled' ) ) {
                if ( $checkbox.is( ':checked' ) ) {
                    $checkbox.removeAttr( 'checked' );
                    $el.removeClass( 'active' );
                } else {
                    $checkbox.attr( 'checked', 'checked' );
                    $el.addClass( 'active' );
                }
            }
        } );

        $( '.js-checked-input.checked' ).closest( 'li' ).trigger( 'click' );

        $switchStepLink.on( 'click', function() {
            var $el = $( this );
            var $container = $( $el.data('switch-step') );

            toTop();

            $( '.es-step.active' ).fadeOut( 'slow', function() {

                $( '.es-step.active' ).removeClass( 'active' );

                $container.fadeIn( 'slow', function() {
                    $container.addClass( 'active' );

                    switch( $el.data('switch-step') ) {

                        case '.es-step__third':
                            $( '[data-step=es-step__second]' ).addClass( 'finished' );
                            $( '[data-step=es-step__third]' ).addClass( 'active' );
                            $( '[data-step=es-step__second] .circle' ).html( '<i class="fa fa-check" aria-hidden="true"></i>' );

                            break;

                        case '.es-step__second':
                            $( '[data-step=es-step__first]' ).addClass( 'finished' );
                            $( '[data-step=es-step__second]' ).addClass( 'active' );
                            $( '[data-step=es-step__first] .circle' ).html( '<i class="fa fa-check" aria-hidden="true"></i>' );
                            $( '[data-step=es-step__third]' ).removeClass( 'active' );
                            $( '[data-step=es-step__second] .circle' ).html('<span>2</span>');

                            break;

                        case '.es-step__first':
                            $( '[data-step=es-step__first]' ).addClass( 'active' );
                            $( '[data-step=es-step__first] .circle' ).html( '<i class="fa fa-check" aria-hidden="true"></i>' );
                            $( '[data-step=es-step__second]' ).removeClass( 'active' ).removeClass('finished');
                            $( '[data-step=es-step__first]' ).removeClass( 'finished' );
                            $( '[data-step=es-step__first] .circle' ).html('<span>1</span>');
                            break;
                    }

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
                    }).show();
                } );
            } );

            return false;
        } );
    } );
} )( jQuery );
