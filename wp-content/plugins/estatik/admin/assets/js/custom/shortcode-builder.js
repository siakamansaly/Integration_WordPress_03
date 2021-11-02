( function( $ ) {
    'use strict';

    $( function() {

        $( 'body' ).magnificPopup( {
            delegate: '.js-es-shortcode-builder__link',
            type: 'ajax'
        } );

        $( document ).on( 'change', '.js-shortcode-field', function() {
            var $resultContainer = $( this ).closest( '#shortcode-builder-popup' ).find( '.js-shortcode-content' );

            $resultContainer.html( Estatik.tr.loading_shortcode_params );

            var data = {
                nonce: $( this ).data('nonce'),
                action: 'es_shortcode_builder_params',
                shortcode: $( this ).val()
            };

            $.get( Estatik.ajaxurl, data, function( response ) {
                $resultContainer.html( response );
                var select = $resultContainer.find( '.js-es-search-fields-select2' );
                var $popup = $( '#shortcode-builder-popup' );

                $( '.js-select2-multiple' ).select2( {
                    tags: true,
                    multiple: true,
                    dropdownParent: $popup,
                } ).val('').trigger('change');

                if ( select.length ) {
                    var ul = $(select).next('.select2-container').first('ul.select2-selection__rendered');

                    ul.sortable({
                        placeholder: "ui-state-highlight",
                        forcePlaceholderSize: true,
                        items: "li:not(.select2-search__field)",
                        tolerance: "pointer",
                        stop: function () {
                            $( $(ul).find(".select2-selection__choice").get().reverse() ).each(function () {
                                var selected = $( select ).select2('data');
                                var value = $( this ).attr('title');
                                for ( var i = 0; i < selected.length; i++ ) {
                                    if ( selected[i].text === value ) {
                                        value = selected[i].id;
                                    }
                                }
                                var option = $(select).find('option[value="' + value + '"]')[0];
                                $(select).prepend(option);
                            });

                            select.trigger( 'change' );
                        }
                    });
                }

                $( '.js-select2' ).select2( {
                    dropdownParent: $popup
                } );

                $( '.js-select2-properties' ).select2( {
                    tags: true,
                    ajax: {
                        url: Estatik.ajaxurl + '?action=es_select2_search_properties&nonce=' + Estatik.settings.admin_nonce,
                        dataType: 'json',
                        minimumInputLength: 3,
                        delay: 500
                    },
                    dropdownParent: $popup
                } );

                $( '[data-tooltipster-content]' ).each( function() {
                    var content = $( this ).data( 'tooltipster-content' );
                    $( this ).tooltipster({
                        contentAsHTML: true,
                        theme: 'tooltipster-borderless',
                        side: ['right'],
                        debug: false
                    }).tooltipster( 'content', content );
                } );
            } );

            if ( $( this ).val() ) {
                $( '.js-insert-shortcode' ).removeClass( 'hidden' );
            } else {
                $( '.js-insert-shortcode' ).addClass( 'hidden' );
            }
        } );

        $( document ).on( 'submit', '.js-es-shortcode-builder-form', function() {
            var editor_id = $( this ).data( 'editor' );
            var $btn = $( '.js-insert-shortcode' );
            var label = $btn.val();

            $btn.val( Estatik.tr.btn_generating );
            // $btn.prop( 'disabled', 'disabled' );

            $.get( Estatik.ajaxurl, $( this ).serialize(), function( response ) {

                if ( ! tinymce.get( editor_id ) ) {
                    $( '#' + editor_id ).val( response ).trigger( 'change' );
                } else {
                    tinymce.get( editor_id ).execCommand('mceInsertContent', false, response );
                }

                $.magnificPopup.close();
            } ).always( function() {
                // $btn.removeProp( 'disabled' );
                $btn.val( label );
            } );

            return false;
        } );
    } );

    $( document ).on( 'click', '.js-es-sb-close', function() {
        $.magnificPopup.close();
    } );
} )( jQuery );
