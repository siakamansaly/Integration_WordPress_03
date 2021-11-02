(function($) {

    function initGoogleSingleMap() {
        var map = document.getElementById('es-google-map');

        var data = $( map ).data();

        if (map && data.lat && data.lon && typeof(EsGoogleMap) != 'undefined' ) {
            var instance = new EsGoogleMap(map, data.lon, data.lat).init();
            instance.setMarker();
        }
    }

    $(function () {
        var hash = document.location.hash.substring(1);

        var $nav = $('.es-single-tabs');

        if ( $nav.length && ! parseInt( Estatik.settings.disable_sticky_property_top_bar ) ) {
            var navPos = parseInt($nav.offset().top);
            var navPosLeft = parseInt($nav.offset().left);
            var navWidth = parseInt($nav.width());

            $(window).scroll(function (e) {
                if($(this).scrollTop() >= navPos){
                    $nav.addClass('es-fixed');
                    $nav.css({'left':navPosLeft+'px','width':navWidth+'px'});
                } else {
                    $nav.removeClass('es-fixed');
                    $nav.css({'left':'0px','width':'auto'});
                }

            });
        }

        jQuery('.es-property-single-fields').magnificPopup({
            delegate: 'a.js-magnific-gallery',
            type: 'image',
            tLoading: 'Loading image #%curr%...',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0,5]
            }
        });

        initGoogleSingleMap();

        if (hash) {
            var $activeTab = $('.es-tab-' + hash).addClass('active');
        } else {
            $('.es-single-tabs').find('a').eq(0).addClass('active');
        }

        $('.es-single-tabs a').each(function() {
             if (!$($(this).attr('href')).length) {
                 $(this).hide();
             }
        });

        $('.es-single-tabs a').on( 'click', function() {
            $('.es-single-tabs a').removeClass('active');
            $(this).addClass('active');

            var target = $(this).attr('href') == '#es-info' ? 'body' : $(this).attr('href');

            $('html, body').animate({
                scrollTop: $(target).offset().top - 50
            }, 600);

            return false;
        });

        $( '.es-top-link' ).on( 'click', function() {

            $('html, body').animate({
                scrollTop: $( 'body' ).offset().top - 50
            }, 600);

            return false;
        } );
    });
})(jQuery);
