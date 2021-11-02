( function( $ ) {
    'use strict';

    $.moveColumn = function (table, from, to) {
        var rows = jQuery('tr', table);
        var cols;
        rows.each(function() {
            cols = jQuery(this).children('th, td');
            cols.eq(from).detach().insertBefore(cols.eq(to));
        });
    };

    $( function() {
        var $select2Boxes = $( '.es-select-2' );
        var $datePicker = $( '.js-datepicker' );
        var $table = $( '.post-type-properties .wp-list-table' );

        // Flag for 782 window width.
        var flag782 = true;

        $( window ).on( 'resize', function() {
            if ( $( window ).width() <= 782 ) {
                if (flag782) {
                    $.moveColumn($table, 3, 1);
                    flag782 = false;
                }
            }
        });

        $( window ).trigger( 'resize' );

        $( '.page-title-action' ).removeClass( 'page-title-action' ).addClass( 'es-button' ).addClass( 'es-button-green' ).addClass( 'es-button-add' );

        // Initialize datepicker.
        $datePicker.datepicker( {
            showOn: "button",
            buttonImage: Estatik.settings.pluginUrl + '/admin/assets/images/es_calender_icon.png',
            buttonImageOnly: true,
            dateFormat: "mm/dd/yy"
        } );

        if ( $select2Boxes.length ) {
            $select2Boxes.select2();
        }

        // Select / deselect button trigger.
        $( '.js-es-select-all' ).on( 'click', function() {
            $( '#cb-select-all-1' ).trigger( 'click' );

            return false;
        } );

        // Append estatik logo to the page.
        if ( Estatik.html.logo ) {
            $( '.post-type-properties .wrap h1' ).before( Estatik.html.logo );
        }

        // Manage action buttons.
       $( '.es-button[data-action]' ).on( 'click', function() {
           var confirmMsg = $( this ).data( 'confirm' );
           var errorMsg = $( this ).data( 'error' );
           var $el = $(this);

           if (!$('.post-type-properties .wp-list-table [type=checkbox]:checked').length) {
               $('.es-message-popup').esPopup('message', errorMsg).esPopup('show');
           } else {
               $('.es-confirm-popup').esPopup('yesCallback', function() {
                   $( 'input[name=es-action]' ).val( $el.data( 'action' ) );
                   $el.closest('form').submit();
                   $(this).esPopup('hide');

                   return false;
               }).esPopup('message', confirmMsg).esPopup('show');
           }

           return false;
       } );

        $('.post-type-properties .trash a').on( 'click', function() {
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
