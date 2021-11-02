( function( $ ) {
    'use strict';

    if ( typeof elementorFrontend !== 'undefined' ) {
        $( window ).on( 'elementor/frontend/init', function () {
            elementorFrontend.hooks.addAction( 'frontend/element_ready/es-listings-widget.default', function ( $scope ) {
                var $listWrapper = $scope.find( '.es-listing' );
                var currentListClass = 'es-layout-' + Estatik.settings.layout;
                var resizeOptions = Estatik.settings.responsive;
                var resizeOptionsClassString = Object.keys(resizeOptions).join(' ');
                var currentResponsiveClass = currentListClass;

                $(window).on('resize', function() {
                    if (!$listWrapper.hasClass('es-layout-1_col')) {
                        // Property width.
                        var contentWidth = $listWrapper.width();

                        if (resizeOptions) {
                            for (var layoutClassName in resizeOptions) {
                                var currentMin = resizeOptions[currentListClass].min;

                                var min = resizeOptions[layoutClassName].min;
                                var max = resizeOptions[layoutClassName].max;

                                if (contentWidth < currentMin || currentResponsiveClass != currentListClass) {
                                    if (contentWidth > min && contentWidth < max) {
                                        $listWrapper.removeClass(resizeOptionsClassString).addClass(layoutClassName);
                                        currentResponsiveClass = layoutClassName;
                                    }
                                }

                                if (contentWidth < 410) {
                                    $listWrapper.addClass('es-col-1');
                                } else {
                                    $listWrapper.removeClass('es-col-1');
                                }
                            }
                        }
                    }
                } );

                $(window).trigger('resize');
            } );

            // Initialize js for properties carousel.
            elementorFrontend.hooks.addAction( 'frontend/element_ready/es-slideshow-widget.default', function ( $scope ) {
                var $el = $scope.find( '.js-es-slideshow' ).not( '.slick-initialized' );
                var numSlides = $el.children().length;
                var item = $el.data( 'args' );

                if ( item ) {
                    var slidesToShow = parseInt(item.slides_to_show) || 1;

                    slidesToShow = slidesToShow >= numSlides && item.slider_effect === 'vertical' ?
                        numSlides : slidesToShow;

                    var responsive = [];

                    if ( slidesToShow > 3 ) {
                        responsive.push( {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 3,
                                // infinite: true,
                                dots: true
                            }
                        } );
                    }

                    if ( slidesToShow > 2 ) {
                        responsive.push( {
                            breakpoint: 600,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 2
                            }
                        } );
                    }

                    responsive.push( {
                        breakpoint: 450,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    } );

                    responsive.push( {
                        breakpoint: 200,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    } );

                    var settings = {
                        margin: 20,
                        slidesToShow: slidesToShow,
                        arrows: 1 === +item.show_arrows || false,
                        prevArrow: '<span class="es-slick-arrow es-slick-prev"></span>',
                        nextArrow: '<span class="es-slick-arrow es-slick-next"></span>',
                        responsive: responsive
                    };

                    if ( ! settings.arrows ) {
                        settings.autoplay = true;
                    }

                    if ( item.slider_effect === 'vertical' ) {
                        settings.vertical = true;
                        settings.verticalSwiping = true;
                        // settings.infinite = false;
                        settings.autoplaySpeed = 5000;
                    }

                    $el.slick( settings );
                }
            } );
        } );
    }
} )( jQuery );