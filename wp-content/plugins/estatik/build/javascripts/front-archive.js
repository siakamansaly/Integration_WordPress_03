(function($) {

    function initGoogleSingleMap(lon, lat) {
        var map = document.getElementById('es-map-inner');

        if (map && lon && lat) {
            var instance = new EsGoogleMap(map, lon, lat).init();
            instance.setMarker();
        }
    }

    $(function() {
        var $listWrapper = $( '.es-listing' );
        var currentListClass = 'es-layout-' + Estatik.settings.layout;
        var resizeOptions = Estatik.settings.responsive;
        var resizeOptionsClassString = Object.keys(resizeOptions).join(' ');
        var currentResponsiveClass = currentListClass;

        $( '.js-es-change-submit' ).on( 'change', function() {
            $( this ).closest( 'form' ).submit();
        } );

        $(window).on('resize', function() {

            $listWrapper.each( function() {

                if ( ! $listWrapper.hasClass( 'es-layout-1_col' ) ) {
                    // Property width.
                    var contentWidth = $( this ).width();

                    if ( resizeOptions ) {
                        for (var layoutClassName in resizeOptions) {
                            var currentMin = resizeOptions[currentListClass].min;

                            var min = resizeOptions[layoutClassName].min;
                            var max = resizeOptions[layoutClassName].max;

                            if (contentWidth < currentMin || currentResponsiveClass != currentListClass) {
                                if (contentWidth > min && contentWidth < max) {
                                    $( this ).removeClass(resizeOptionsClassString).addClass(layoutClassName);
                                    currentResponsiveClass = layoutClassName;
                                }
                            }

                            if (contentWidth < 410) {
                                $( this ).addClass('es-col-1');
                            } else {
                                $( this ).removeClass('es-col-1');
                            }
                        }
                    }
                }
            } );
        });

        $( window ).trigger( 'resize' );

        $( '.es-map-view-link' ).on( 'click', function() {
            var lon = parseFloat( $( this ).data( 'longitude' ) ), lat = parseFloat( $( this ).data( 'latitude' ) );

            $.magnificPopup.open( {
                items: {
                    src: '#es-map-popup'
                },
                type: 'inline',
                mainClass: 'mfp-fade'
            } );

            initGoogleSingleMap( lon, lat );

            return false;
        });
    });
})(jQuery);
