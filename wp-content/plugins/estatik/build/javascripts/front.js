( function( $ ) {
    'use strict';

    var priority = Estatik.widgets.search.initPriority;

    /**
     * Append select field options.
     *
     * @param items
     * @param $el
     */
    function appendOptions( items, $el ) {
        items = JSON.parse(items);

        $el.html('');

        if (items) {
            var label = $el.find('option[value=0]').html();
            label = label ? label : Estatik.tr.select_location;
            $el.html('<option value="">' + label + '</option>');
            for (var i in items) {
                if ( ! items[i].long_name ) continue;
                $el.append('<option value="' + items[i].id + '">' + items[i].long_name + '</option>');
            }
            $el.removeProp('disabled').removeAttr('disabled');
        }
    }

    /**
     * Load location items.
     *
     * @param object
     * @param $el
     */
    function loadItems( object, $el ) {
        object.action = 'es_get_location_items';
        object.nonce = Estatik.settings.front_nonce;
        $.post( Estatik.ajaxurl, object, function( response ) {
            appendOptions( response, $el );
            var val = $el.data( 'value' );
            if ( val ) {
                $el.val( val );
                $el.trigger( 'change' );
            }
        });
    }

    /**
     * Initialize base field location.
     *
     * @param priority
     */
    function initBaseLocation( priority ) {
        if ( priority ) {
            $( '.es-search__wrapper, .ept-search, .ert-search, .ept-search__wrapper, .ert-search__wrapper' ).each( function() {
                for ( var i in priority ) {
                    var $initField = $( this ).find( '.js-es-search-' + i );
                    if ( $initField.length ) {
                        loadItems( {type: i, status: 'initialize'}, $initField );
                        break;
                    }
                }
            } );
        }
    }

    $( function() {
        var $searchWrap = $( '.es-search__wrapper' );
        var $requestForm = $( '.es-request-widget-wrap form' );
        var $responseBlock = $( '.es-response-block' );
        var $select2Tags = $( '.es-select2-tags' );
        var $select2Base = $( '.js-es-select2-base' );

        var $singleSlickGallery = $('.es-gallery-image');
        var $singleSlickGalleryPager = $('.es-gallery-image-pager');

        $( '.js-es-select2-sort' ).select2( {
            dropdownCssClass: "es-select2__dropdown es-select2__dropdown--positioning",
            minimumResultsForSearch: -1
        } );

        if ($singleSlickGallery.length ) {
            $singleSlickGallery.slick({
                arrows: false,
                asNavFor: $singleSlickGalleryPager,
                rtl: Estatik.settings.isRTL,
                adaptiveHeight: true,
            });

            var show = 5;

            if ($singleSlickGallery.width() < 430) {
                show = 4;
            }

            $singleSlickGalleryPager.slick({
                asNavFor: $singleSlickGallery,
                slidesToScroll: 1,
                slidesToShow: show,
                focusOnSelect: true,
                arrows: false,
                rtl: Estatik.settings.isRTL,
                // centerMode: true,
                // nextArrow: $('.es-single-gallery-slick-next'),
                // prevArrow: $('.es-single-gallery-slick-prev'),
                responsive: [
                    {
                        breakpoint: 1130,
                        settings: {
                            slidesToShow: 4
                        }
                    },
                    {
                        breakpoint: 780,
                        settings: {
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 320,
                        settings: {
                            slidesToShow: 2
                        }
                    }
                ]
            });

            $('.es-single-gallery-slick-next').on( 'click', function() {
                $singleSlickGallery.slick('slickNext');
                $singleSlickGalleryPager.slick('slickNext');
                return false;
            });

            $('.es-single-gallery-slick-prev').on( 'click', function() {
                $singleSlickGallery.slick('slickPrev');
                $singleSlickGalleryPager.slick('slickPrev');
                return false;
            });

            var is_gallery_disabled = +Estatik.settings.is_lightbox_disabled;

            if ( ! is_gallery_disabled ) {
                jQuery('.es-gallery-image').magnificPopup({
                    delegate: 'a:not(.slick-cloned a)',
                    type: 'image',
                    tLoading: 'Loading image #%curr%...',
                    mainClass: 'mfp-img-mobile',
                    gallery: {
                        enabled: true,
                        navigateByImgClick: true,
                        preload: [0,5] // Will preload 0 - before current, and 1 after the current image
                    }
                });
            }
        }

        initBaseLocation( priority );

        $( document ).on( 'change', '.js-es-location', function() {
            var $el = $( this );
            var type = $el.data( 'field' );
            var val = $el.val();
            var $form = $( this ).closest( 'form' );

            if ( priority[ type ] && val ) {

                for ( var i in priority[ type ] ) {
                    if ( typeof priority[type][i] == 'string' ) {
                        var $depEl = $form.find( '.js-es-search-' + priority[type][i] );
                        if ( $depEl && $depEl.length ) {
                            loadItems( {type: priority[type][i], status: 'dependency', val: val}, $depEl );
                        }
                    }
                }
            }
        } );

        $( '.js-es-tabs' ).each( function() {
            var $links_container = $( this ).find( '.js-es-tabs__links' );
            var $content_container = $( this ).find( '.js-es-tabs__content' );

            $links_container.on( 'click touch tap', 'a', function() {
                var $container = $( $( this ).attr( 'href' ) );
                $links_container.find( 'li' ).removeClass( 'active' );
                $( this ).closest( 'li' ).addClass( 'active' );
                $content_container.find( '.js-es-tabs__tab' ).removeClass( 'active' );
                $container.addClass( 'active' );
                $( window ).trigger( 'resize' );
                // window.location.hash = $( this ).attr( 'href' );

                return false;
            } );

            var hash = window.location.hash;

            if ( hash && $content_container.find( hash ).length ) {
                $links_container.find( 'a[href="' + hash + '"]' ).trigger( 'click' ).trigger( 'touch' );
            } else if ( ! $content_container.find( '.js-es-tabs__tab.active' ).length ) {
                $links_container.find( 'li:first-child a' ).trigger( 'click' ).trigger( 'touch' );
            }
        } );

        if ( $select2Tags.length ) {
            $select2Tags.select2( {
                tags: true,
                dropdownCssClass: "es-select2__dropdown es-select2__dropdown--positioning",
            } );
        }

        if ( $select2Base.length ) {
            $select2Base.select2( {
                dropdownCssClass: "es-select2__dropdown es-select2__dropdown--positioning",
            } );
        }

        $searchWrap.find( 'select:not(.es-select2-tags)' ).select2( {
            dropdownCssClass: "es-select2__dropdown es-select2__dropdown--positioning",
        } );

        setTimeout( function() {
            $searchWrap.find( 'select.select2-hidden-accessible' ).trigger( 'change' );
        }, 200 );

        $requestForm.on('submit', function() {

            var formData = $( this ).serialize();
            var $form = $( this );

            $.post( Estatik.ajaxurl, formData, function( response ) {
                response = JSON.parse( response );
                $responseBlock.html( response );
                $form.hide();
            } );

            return false;
        });

        $( document ).on( 'click', '.js-es-request-form-show', function() {
            $requestForm.show();

            $responseBlock.html('');
            $requestForm.find( 'input[type=text], input[type=tel], input[type=email], textarea' ).val( '' );

            if (typeof grecaptcha !== 'undefined') {
                if ( Estatik.settings.recaptcha_version === 'v2' ) {
                    grecaptcha.reset();
                }
            }
        });

        $searchWrap.find('input[type=reset]' ).on( 'click', function() {
            var $form = $( this ).closest( 'form' );

            $form.find( '.es-search__field' ).find( 'input, select' )
                .val( null )
                .attr( 'value', '' )
                .attr( 'data-value', '' )
                .trigger( 'change' );

            var $select2Tags = $form.find( '.select2-hidden-accessible' );

            if ( $select2Tags.length ) {
                $select2Tags.select2( 'val', '' );
                $select2Tags.select2( 'data', null );
                $select2Tags.find( 'option' ).removeProp( 'selected' ).removeAttr( 'selected' );
            }
        } );

        // Upload ling on register page.
        $( '.js-trigger-upload' ).on( 'click', function() {
            $( $( this ).data('selector') ).trigger( 'click' );

            return false;
        } );

        // Input on register page.
        $( '.js-es-input-image' ).on( 'change', function() {
            var el = this;
            var reader = new FileReader();

            reader.onload = function( e ) {
                $( el ).closest( 'div' ).find( '.js-es-image' ).html( "<img src='" + e.target.result + "'>" );
            };

            reader.readAsDataURL( el.files[0] );

            $( '.js-trigger-upload span' ).html( Estatik.tr.replace_photo );
        } );

        $( '.js-autocomplete-wrap input' ).on( 'keyup', function() {
            var $el = $( this );
            var action = $( this ).data( 'action' );

            $.post( Estatik.ajaxurl, {
                action: action,
                nonce: Estatik.settings.front_nonce,
                s: $( this ).val()
            }, function( response ) {
                $el.closest( '.js-autocomplete-wrap' ).find( '.es-autocomplete-result' ).html( response );
            } );
        } );

        $( '.js-autocomplete-wrap' ).on( 'click', 'li', function( e ) {
            var $el = $( this );
            var $parent = $el.closest( '.js-autocomplete-wrap' );
            $parent.find( 'input' ).val( $el.data( 'content' ) );
            $parent.find( '.es-autocomplete-result' ).html('');
            e.stopPropagation();
        } );

        $( 'body' ).on( 'click', function(){
            $( '.es-autocomplete-result' ).html( '' );
        });

        resizeCaptcha( $( '.es-recaptcha-wrapper .g-recaptcha' ) );

        $(window).on('resize', function() {
            resizeCaptcha( $( '.es-recaptcha-wrapper .g-recaptcha' ) );
        });

        function resizeCaptcha( captchaElements ) {
            if ( ! captchaElements.closest('.contact-block__send-form-wrapper').length ) {
                var recaptcha = captchaElements;

                var captchaResized = false;
                var captchaWidth = 304;
                var captchaHeight = 78;
                var captchaWrapper = captchaElements.closest( '.es-recaptcha-wrapper' );

                if (captchaWrapper.width() >= captchaWidth) {
                    if (captchaResized) {
                        captchaElements.css('transform', '').css('-webkit-transform', '').css('-ms-transform', '').css('-o-transform', '').css('transform-origin', '').css('-webkit-transform-origin', '').css('-ms-transform-origin', '').css('-o-transform-origin', '');
                        captchaWrapper.height(captchaHeight);
                        captchaResized = false;
                    }
                } else {
                    var scale = (1 - (captchaWidth - captchaWrapper.width()) * (0.05/15));
                    captchaElements.css('transform', 'scale('+scale+')').css('-webkit-transform', 'scale('+scale+')').css('-ms-transform', 'scale('+scale+')').css('-o-transform', 'scale('+scale+')').css('transform-origin', '0 0').css('-webkit-transform-origin', '0 0').css('-ms-transform-origin', '0 0').css('-o-transform-origin', '0 0');
                    captchaWrapper.height(captchaHeight * scale);
                    if (captchaResized == false) captchaResized = true;
                }
            }
        }

        $( document ).on( 'click', '.js-es-wishlist-button', function() {

            var $link = $( this );
            $link.removeClass( 'error' );
            if ( ! $link.hasClass( 'preload' ) ) {
                var data = $link.data();
                data.action = 'es_wishlist_' + data.method;
                data.nonce = Estatik.settings.wishlist_nonce;
                $link.addClass( 'preload' );
                var $container = $link.closest( '#es-saved-homes' );
                var $item = $link.closest( '.properties' );

                $.post( Estatik.ajaxurl, data, function( response ) {

                    response = response || {};

                    if ( response.status === 'success' ) {
                        $( $link ).replaceWith( response.data );

                        if ( $container.length ) {
                            $item.fadeOut( 400, function() {
                                $item.remove();
                            } );
                        }
                    } else {
                        $link.addClass( 'error' );
                        if ( response.message ) {
                            alert( response.message );
                        }
                    }
                }, 'json' ).always( function() {
                    $link.removeClass( 'preload' );
                } ).fail( function() {
                    $link.addClass( 'error' );
                } );
            }

            return false;
        } );

        $( '.js-es-save-search' ).on( 'click', function() {
            var $btn = $( this );
            var $form = $btn.closest( 'form' );

            var label = $btn.val();

            $btn.val( Estatik.tr.saving );

            var data = new FormData( $form[0] );
            data.append( 'action', 'es_save_search' );
            data.append( 'nonce', Estatik.settings.save_search_nonce );

            if ( ! $btn.hasClass( 'es-button-green-corner' ) ) {
                $btn.addClass( 'es-button-green-corner' );
            }

            $form.find( '.js-es-search__messages' ).html('');

            $.ajax( {
                url: Estatik.ajaxurl,
                type: 'POST',
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function( response ) {
                    response = response || {};

                    if ( response.status === 'success' ) {
                        $btn.val( Estatik.tr.saved );
                    } else if ( response.status === 'error' ) {
                        $btn.val( Estatik.tr.error );
                        $btn.removeClass( 'es-button-green-corner' ).addClass( 'es-button-error' );
                    }

                    if ( response.message ) {
                        $form.find( '.js-es-search__messages' ).html( response.message );
                    }
                }
            } ).always( function() {

            } ).fail( function() {
                $btn.val( Estatik.tr.error );
                $btn.removeClass( 'es-button-green-corner' ).addClass( 'es-button-error' );
            } );
        } );

        $( '.js-es-change-update-method' ).on( 'change', function() {

            var $el = $( this );
            var $msg_container = $el.closest( 'form' ).find( '.es-msg-container' );

            $msg_container.html( '' );

            var data = {
                action: 'es_change_update_method',
                nonce: Estatik.settings.save_search_change_method_nonce,
                id: $( this ).data( 'id' ),
                update_method: $( this ).val()
            };

            $.post( Estatik.ajaxurl, data, function( response ) {
                response = response || {};

                if ( response.message ) {

                    if ( response.status === 'success' ) {
                        response.message = '<p class="es-message es-message-success"><i class="fa fa-check-circle-o" aria-hidden="true"></i> ' + response.message +'</p>';
                    } else {
                        response.message = '<p class="es-message es-message-error"><i class="fa fa-times-circle-o" aria-hidden="true"></i> ' + response.message +'</p>';
                    }
                    $msg_container.html( response.message );
                }
            }, 'json' ).fail( function() {
                alert( Estatik.tr.system_error );
            } );
        } );

        $( '.js-es-login-form' ).on( 'click', function() {

            $.get( Estatik.ajaxurl, { action: 'es_login_form', nonce: Estatik.settings.front_nonce }, function( response ) {

                $.magnificPopup.open( {
                    items: {
                        src: response,
                        type: 'inline'
                    }
                } );

            } ).fail( function() {
                alert( Estatik.tr.system_error );
            } );

            return false;
        } );

        var $profile_wrapper = $( '.es-profile__wrapper--horizontal' );
        var $profile_nav = $( '.es-profile__wrapper--horizontal .es-profile__tabs-wrapper' );

        $( window ).on( 'resize', function() {
            var nav_width = 0;

            $profile_nav.find( 'li' ).each( function() {
                nav_width += $( this ).outerWidth();
            } );

            if ( $profile_nav.find( 'ul' ).hasClass( 'slick-initialized' ) ) {
                $profile_nav.find( 'ul' ).slick( 'unslick' );
            }

            if ( $profile_wrapper.width() < nav_width ) {
                $profile_nav.find( 'ul' ).slick( {
                    variableWidth: true,
                    prevArrow: '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                    nextArrow: '<i class="fa fa-angle-right" aria-hidden="true"></i>'
                } );
            }
        } );

        if ( $( window ).length ) {
            $( window ).trigger( 'resize' );
        }

        $( '.js-switch-block' ).on( 'click', function() {
            var $container = $( $( this ).data( 'block' ) );

            if ( $container.hasClass( 'hidden' ) ) {
                $container.removeClass( 'hidden' );
            } else {
                $container.addClass( 'hidden' );
            }

            return false;
        } );

        $( '.js-saved-search-save' ).on( 'click', function() {
            var $container = $( this ).closest( '.es-saved-search__item' );
            var $msg_container = $container.find( '.es-msg-container' );

            $.post( Estatik.ajaxurl, $( this ).closest( 'form' ).serialize(), function( response ) {
                response = response || {};

                if ( response.message ) {

                    if ( response.status === 'success' ) {
                        response.message = '<p class="es-message es-message-success"><i class="fa fa-check-circle-o" aria-hidden="true"></i> ' + response.message +'</p>';
                        $container.find( '.js-saved-search-title' ).html( response.title );
                    } else {
                        response.message = '<p class="es-message es-message-error"><i class="fa fa-times-circle-o" aria-hidden="true"></i> ' + response.message +'</p>';
                    }
                    $msg_container.html( response.message );
                }
            }, 'json' ).fail( function() {
                alert( Estatik.tr.system_error );
            } ).always( function() {
                $container.find( '.js-switch-block' ).trigger( 'click' );
            } );

            return false;
        } );

        var $properties_slideshow = $( '.js-es-slideshow' );

        if ( $properties_slideshow.length ) {
            $properties_slideshow.each( function() {

                var $el = $( this );
                var numSlides = $el.children().length;
                var item = $el.data( 'args' );
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
                    adaptiveHeight: true,
                    margin: 20,
                    slidesToShow: slidesToShow,
                    arrows: 1 == item.show_arrows || false,
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
            } );
        }

        $( '.es-slide__bottom' ).each(function() {
            var $this = $(this);

            if ( $this.find( '.es-bottom-icon' ).length === 2 ) {
                $this.find( '.es-bottom-icon:last-child' ).css( { 'text-align': 'right' } );
            }
        } );
    } );
})( jQuery );
