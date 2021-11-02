( function( $ ) {
    'use strict';

    $( function() {

        var $tooltipsterDragAvailable = $( '.js-es__available-tooltipster--drag' );
        var $tooltipsterRetsAvailable = $( '.js-es__available-tooltipster--rets' );
        var $tooltipsterSearchAvailable = $( '.js-es__available-tooltipster--search' );
        var $tooltipsterSearchMultiple = $( '.js-es__available-tooltipster--multiple' );

        var toolTipsterConfig = {
            contentAsHTML: true,
            theme: 'tooltipster-borderless',
            side: ['right'],
            debug: false,
            // content: Estatik.tr.searchAvailable,
            interactive: true
        };

        var cloneyaConfig = {
            cloneThis: '.es-field',
            cloneButton	: '.clone',
            deleteButton: '.delete'
        };

        $tooltipsterSearchMultiple.tooltipster( toolTipsterConfig ).tooltipster( 'content', Estatik.tr.multipleInput );

        $tooltipsterDragAvailable.tooltipster( toolTipsterConfig ).tooltipster( 'content', Estatik.tr.dragndropAvailable );

        $tooltipsterRetsAvailable.tooltipster( toolTipsterConfig ).tooltipster( 'content', Estatik.tr.retsAvailable );

        $tooltipsterSearchAvailable.tooltipster( toolTipsterConfig ).tooltipster( 'content', Estatik.tr.searchAvailable );

        $('.es-clone__wrap').cloneya( cloneyaConfig );

        $( '.js-es-load-options-fields' ).on( 'change', function() {
            var type = $( this ).val();

            if ( type ) {
                $.post( ajaxurl, { nonce: Estatik.settings.admin_nonce, action: 'es_fbuilder_load_field_options', type: type }, function( response ) {
                    $( '.js-es-fbuilder__field-options' ).html( response );
                    $('.es-clone__wrap').cloneya( cloneyaConfig );

                    $( '.es-switch-input:not(.es-checkbox)' ).esCheckbox({
                        labelTrue: Estatik.tr.yes,
                        labelFalse: Estatik.tr.no
                    });

                    $( '.js-es__available-tooltipster--multiple' ).tooltipster( toolTipsterConfig ).tooltipster( 'content', Estatik.tr.multipleInput );

                } );
            }
        } );
    } );
} )( jQuery );
