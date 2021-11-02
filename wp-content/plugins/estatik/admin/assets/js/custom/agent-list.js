( function( $ ) {
    'use strict';

    $.moveColumn = function ( table, from, to ) {
        var rows = $( 'tr', table );
        var cols;
        rows.each( function() {
            cols = $( this ).children('th, td');
            cols.eq( from ).detach().insertBefore( cols.eq( to ) );
        });
    };

    $( function() {
        var $table = $( '.es-agent-list-page .wp-list-table' );

        // Flag for 782 window width.
        var flag782 = true;

        $( window ).resize( function() {
            if ( $( window ).width() <= 782 ) {
                if ( flag782 ) {
                    $.moveColumn( $table, 2, 1 );
                    flag782 = false;
                }
            }
        } );

        $( window ).trigger( 'resize' );

        if ( $( 'body' ).hasClass( 'es-agent-list-page' ) ) {
            $( '.page-title-action' )
                .removeClass( 'page-title-action' )
                .addClass( 'es-button' )
                .addClass( 'es-button-green' )
                .addClass( 'es-button-add' )
                .prop( 'href', EstatikUserList.add_user_url );
        }

        // Select / deselect button trigger.
        $( '.js-es-select-all' ).on( 'click', function() {
            $( '#cb-select-all-1' ).trigger( 'click' );

            return false;
        } );

        // Append estatik logo to the page.
        if ( Estatik.html.logo ) {
            $( '.es-agent-list-page .wrap h1' ).before( Estatik.html.logo );
        }

        // Manage action buttons.
        $( '.es-button[data-action]' ).on( 'click', function() {
            var confirmMsg = $( this ).data( 'confirm' );
            var errorMsg = $( this ).data( 'error' );
            var $el = $(this);

            if (!$('.es-agent-list-page .wp-list-table [type=checkbox]:checked').length) {
                $('.es-message-popup').esPopup('message', errorMsg).esPopup('show');
            } else {
                $('.es-confirm-popup').esPopup('yesCallback', function() {
                    if ( $el.data( 'action' ) === 'delete' ) {
                        $( '#bulk-action-selector-top' ).val( $el.data( 'action' ) ).change();
                    } else {
                        $( 'input[name=es-action]' ).val( $el.data( 'action' ) );
                    }

                    $el.closest('form').submit();
                    $(this).esPopup('hide');

                    return false;
                }).esPopup('message', confirmMsg).esPopup('show');
            }

            return false;
        } );

        $('.es-agent-list-page .trash a').on( 'click', function() {
            var $el = $(this);
            var href = $el.attr('href');

            $('.es-confirm-popup').esPopup('yesCallback', function() {
                window.location.href = href;
                $(this).esPopup('hide');

                return false;
            }).esPopup('message', Estatik.tr.confirmDeleting).esPopup('show');

            return false;
        });

        $('.es-delete-user').on( 'click', function() {
            var $el = $(this);
            var href = $el.attr('href');

            $('.es-confirm-popup').esPopup('yesCallback', function() {
                window.location.href = href;
                $(this).esPopup('hide');

                return false;
            }).esPopup('message', Estatik.tr.confirmDeleting).esPopup('show');

            return false;
        })
    } );
} ) ( jQuery );
