(function($) {
    'use strict';

    /**
     * Initialize admin property google map.
     */
    function esInitMetaboxMap() {
        var $lon = $('#es-longitude-input').val();
        var $lat = $('#es-latitude-input').val();
        var map = document.getElementById('es-property-map');

        if ($lon && $lat && map && EsGoogleMap != 'undefined') {
            var instance = new EsGoogleMap(map, $lon, $lat).init();
            instance.setMarker( false, true );

            map.classList.add('es-map-border');
        } else {
            if (map) {
                map.classList.remove('es-map-border');
            }
        }
    }

    /**
     * Change display css param in $searchPagesContainer using $el value.
     *
     * @param $el
     * @param $searchPagesContainer
     */
    function esSwitchSearchPagesContainerState($el, $searchPagesContainer) {
        var val = $el.val();

        if (val == 'all') {
            $searchPagesContainer.removeClass('show');
        } else {
            $searchPagesContainer.addClass('show');
        }
    }

    $(function() {
        var $unitsTooltipFields = $( '.es-field-area-unit input' );
        var map = document.getElementById('es-property-map');
        var addressInput = document.getElementById('es-address-input');
        var $propMetaBoxDataTabs = $('.property-data-tabs');
        var $dataManagerItems = $('.es-data-manager-item');
        var $themesDashboardSlider = $('.es-themes-slider');
        var $styledCheckboxes = $('.es-switch-input');
        var $layoutRadio = $('.js-es-layout-checkbox');
        var $scrollList = $('.es-scroll-list');
        var $confirmPopup = $('.es-confirm-popup');
        var $messagePopup = $('.es-message-popup');

        if ( typeof elementor !== 'undefined' ) {
            elementor.hooks.addAction( 'panel/open_editor/widget/es-search-widget', function( panel ) {
                var $panel = panel.$el;

                var select = $panel.find( '.elementor-select2[multiple]' );
                var ul = $(select).next('.select2-container').first('ul.select2-selection__rendered');

                ul.sortable({
                    placeholder: "ui-state-highlight",
                    forcePlaceholderSize: true,
                    items: "li:not(.select2-search__field)",
                    tolerance: "pointer",
                    stop: function () {
                        $($(ul).find(".select2-selection__choice").get().reverse()).each(function () {
                            var selected = $(select).select2('data');
                            var value = $(this).attr('title');
                            for (var i = 0; i < selected.length; i++) {
                                if (selected[i].text == value) {
                                    value = selected[i].id;
                                }
                            }
                            var option = $(select).find('option[value="' + value + '"]')[0];
                            $(select).prepend(option);
                        });

                        select.trigger( 'change' );
                    }
                });
            } );
        }

        $( document ).on( 'change es-checkbox-changed', '[data-toggle-container]', function() {
            var $container = $( $( this ).data('toggle-container') );

            if ( $( this ).is( ':checked' ) ) {
                $container.removeClass( 'es-hidden' ) ;
            } else {
                $container.addClass( 'es-hidden' );
            }
        } );

        $( '[data-toggle-container]' ).trigger( 'change' );

        $( document ).on( 'change', '.js-es-send-to', function() {
            if ( $( this ).val() === '-1' ) {
                $( this ).closest( '.es-widget-wrap' ).find( '.js-es-send-to-field' ).removeClass( 'hidden' );
            } else {
                $( this ).closest( '.es-widget-wrap' ).find( '.js-es-send-to-field' ).addClass( 'hidden' );
            }
        } ).trigger( 'change' );

        if ( typeof $.fn.esdatetimepicker !== 'undefined' ) {
            $( '.es-field-date input' ).esdatetimepicker( {
                format: Estatik.settings.dateFormat,
                timepicker: false
            } );
        }

        if ( $( '.js-es-color-picker' ).length ) {
            $( '.js-es-color-picker' ).wpColorPicker();
        }

        $( '.nav-tab-wrapper .nav-tab-menu li a' ).on( 'click', function() {
            var $wrapper = $( this ).closest( '.nav-tab-wrapper' );
            var tabContainer = $( this ).attr( 'href' );

            if ( tabContainer.length > 1 ) {

                $wrapper.find( 'li' ).removeClass( 'active' );
                $( this ).closest( 'li' ).addClass( 'active' );

                $wrapper.find( '.es-tab' ).hide();
                $( tabContainer ).show();

                var yScroll = document.body.scrollTop;

                if ( history.pushState ) {
                    history.pushState( null, null, tabContainer );
                }
                else {
                    location.hash = tabContainer;
                }

                document.body.scrollTop = yScroll;
            }

            return false;
        } );


        try {
            if ( window.location.hash ) {
                if ( $( window.location.hash ).length && $( window.location.hash ).hasClass( 'es-tab' ) ) {
                    $( "a[href='" + window.location.hash + "']" ).trigger( 'click' );
                }
            }
        } catch (e) {
            console.log("Invalid hash");
        }

        $( '.nav-tab-wrapper' ).each( function() {
            if ( ! $( this ).find( '.nav-tab-menu li.active' ).length ) {
                $( this ).find( '.nav-tab-menu li:first-child a' ).trigger( 'click' );
            }

            window.scrollTo(0, 0);         // execute it straight away
            setTimeout(function() {
                window.scrollTo(0, 0);     // run it a bit later also for browser compatibility
            }, 1);
        } );

        if ( typeof $.fn.esdatetimepicker !== 'undefined' ) {
            $('.es-field-datetime-local input').esdatetimepicker({
                format: Estatik.settings.dateTimeFormat
            });
        }

        if ($confirmPopup.length) {
            $confirmPopup.esPopup({confirmPopup: true});
        }

        if ($messagePopup.length) {
            $messagePopup.esPopup();
        }

        // Dashboard custom scroll for list blocks.
        if ($scrollList.length) {
            $scrollList.mCustomScrollbar();
        }

        if ($layoutRadio.length) {
            $layoutRadio.on( 'change', function() {
                var $el = $(this);
                var $wrap = $el.closest('.es-layout-wrap');

                $wrap.find('.es-sprite').removeClass('es-sprite-active');
                $el.closest('.es-layout-box').find('.es-sprite').addClass('es-sprite-active');
            });
        }

        $( '.js-es-remove-attachment' ).on( 'click', function() {
            var $el = $( this );
            $el.closest( '.es-field__content' ).find( 'input' ).val( '' );
            $el.closest( '.es-manage-attachments' ).remove();

            return false;

        } );

        if ($styledCheckboxes.length) {
            $styledCheckboxes.esCheckbox({
                labelTrue: Estatik.tr.yes,
                labelFalse: Estatik.tr.no
            });
        }

        var $tabs = $('.nav-tab-wrapper');

        if ($tabs.length) {
            // $tabs.tabs();
        }

        if ($themesDashboardSlider.length) {
            $themesDashboardSlider.slick({
                arrows: true,
                slidesToShow: 4,
                // centerMode: true,
                // centerPadding: '10px',
                responsive: [
                    {
                        breakpoint: 1550,
                        settings: {
                            slidesToShow: 4,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 1290,
                        settings: {
                            slidesToShow: 3,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });
        }

        if ($dataManagerItems.length) {
            $dataManagerItems.each(function() {
                $(this).dataManagerItem();
            })
        }

        var $searchWidgetFieldsList = $('.es-search-widget-fields');

        var $showPagesSearchField = $('.js-show-search-pages');
        var $searchPagesContainer = $('.js-search-pages');

        $( document ).ajaxSuccess( function() {
            var $searchWidgetFieldsList = $('.es-search-widget-fields');
            var $showPagesSearchField = $('.js-show-search-pages');
            // var $searchPagesContainer = $('.js-search-pages');

            if ($searchWidgetFieldsList.length) {
                $searchWidgetFieldsList.sortable({
                    change: function( event, ui ) {
                        $searchWidgetFieldsList.closest( '.es-search-widget__wrap' ).find( 'input' ).trigger( 'change' );
                    }
                });
            }

            if ($showPagesSearchField.length) {
                $showPagesSearchField.each(function() {
                    var $el = $(this);
                    esSwitchSearchPagesContainerState($el, $el.closest('.es-widget__wrap').find('.js-search-pages'));
                });
            }
        } );

        $(document).on('change', '.js-show-search-pages', function() {
            var $el = $(this);
            esSwitchSearchPagesContainerState($(this), $el.closest('.es-widget__wrap').find('.js-search-pages'));
        });

        if ($showPagesSearchField.length) {
            $showPagesSearchField.each(function() {
                var $el = $(this);
                esSwitchSearchPagesContainerState($el, $el.closest('.es-widget__wrap').find('.js-search-pages'));
            });
        }

        $(document).on( 'change', '.js-es-field-select', function() {
            var $el = $( this );
            var $option = $el.find( 'option:selected' );
            var value = $el.val();
            var $searchWidgetFieldsList = $( this ).closest( '.es-search-widget__wrap' ).find( '.es-search-widget-fields' );
            var name = $(this).closest( '.es-search-widget__wrap' ).find( '.es-fields-name' ).attr( 'name' );

            if ( value ) {
                if ( ! $searchWidgetFieldsList.find('li[data-field-name=' + value + ']').length ) {
                    $searchWidgetFieldsList.append( '' +
                        '<li data-field-name="' + value + '">' + $option.html() + '<a href="#" class="es-remove-field">×</a>' +
                        '<input type="hidden" name="' + name + '" value="' + value + '"></li>' );
                }
            }
            $el.val( '' );

            $el.closest( 'form' ).find( 'input' ).trigger( 'change' );
        });

        if ( $searchWidgetFieldsList.length ) {
            $searchWidgetFieldsList.sortable( {
                change: function( event, ui ) {
                    $searchWidgetFieldsList.closest( '.es-search-widget__wrap' ).find( 'input' ).trigger( 'change' );
                }
            } );
        }

        $( document ).on( 'click', '.es-remove-field', function() {
            $( this ).closest( '.es-search-widget__wrap' ).find( 'input' ).trigger( 'change' );
            $( this ).closest( 'li' ).remove();

            return false;
        } );

        // Initialize property data meta box tabs.
        if ( $propMetaBoxDataTabs.length ) {
            esInitMetaboxMap();
        }

        $( document ).on( 'click', '.wp-admin.post-type-properties #post [type=submit]', function() {
            $( '.property-data-tabs' ).find('input, textarea, select').filter('[required]:hidden').each(function(){
                if ( ! $(this)[0].checkValidity() ) {
                    var tabId = $( this ).closest('.es-tab').attr( 'id' );
                    $( '.nav-tab-menu' ).find( '[href="#' + tabId + '"]' ).trigger( 'click' );
                    return false;
                }
            });
        } );

        $('.js-es-add-custom').on( 'click', function() {
            var value = $('[name=es-custom-field]').val();

            if (value) {
                var content = '<div class="es-field es-field-custom">' +
                    '<div class="es-field__label">' + value + '</div>' +
                    '<div class="es-field__content">' +
                    '<input type="text" name="es_custom_value[]"/>' +
                    '<input type="hidden" name="es_custom_key[]" value="' + value + '"/>' +
                    '<a href="#" class="js-es-remove-custom"><span class="es-sprite es-sprite-close"></span></a>' +
                    '</div>' +
                    '</div>';

                $('.es-property-custom-wrap').before(content);
            }

            $('[name=es-custom-field]').val('');

            return false;
        });

        $(document).on('click', '.js-es-remove-custom', function() {
            $(this).closest('.property-data-field, .es-field-custom').remove();

            return false;
        });

        // Reinit metabox map after change coordinates.
        $('#es-latitude-input, #es-longitude-input').on( 'change', esInitMetaboxMap);

        if (addressInput) {
            var autocomplete = new google.maps.places.Autocomplete(addressInput);

            // Reinit map when address changed.
            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var result = autocomplete.getPlace();

                if (typeof result !== 'undefined') {
                    var location = result.geometry.location;
                    $('#es-latitude-input').val(location.lat());
                    $('#es-longitude-input').val(location.lng());

                    // $('#es-address-input').val(result.formatted_address);
                    $('#es-address_components-input').val(JSON.stringify(result.address_components));

                    esInitMetaboxMap();
                }
            })
        }

        $unitsTooltipFields.on('change click focus', function () {
            var $content = $(this).closest('.es-field__content');
            var val = $content.find('input').val();
            var unit = $content.find('select').val();

            $('.tooltipstered').tooltipster({}).tooltipster('close');

            $.post(ajaxurl, {val: val, unit: unit, action: 'es_calculate_units', nonce: Estatik.settings.admin_nonce}, function(response) {
                response = response || {};

                if (response.status) {
                    $content.find('select').tooltipster({
                        contentAsHTML: true,
                        theme: 'tooltipster-borderless',
                        side: ['right'],
                        debug: false
                    }).tooltipster('content', response.content).tooltipster('open');
                }
            }, 'json');
        }).on('mouseleave', function() {
            $('.tooltipstered').tooltipster({}).tooltipster('close');
        });

        $( '[data-tooltipster-content]' ).each( function() {
            var content = $( this ).data( 'tooltipster-content' );
            $( this ).tooltipster({
                contentAsHTML: true,
                theme: 'tooltipster-borderless',
                side: ['right'],
                debug: false
            }).tooltipster( 'content', content );
        } );

        $( '.js-search-input' ).on( 'keyup', function() {
            var $elements = $( $( this ).data( 'search-selector' ) );
            var q = $( this ).val().toUpperCase();

            $elements.each( function() {
                if ( $( this ).html().toUpperCase().indexOf( q ) > -1 ) {
                    $( this ).show();
                } else {
                    $( this ).hide();
                }
            } );
        } );

        var file_frame;

        $(document).on('click', 'a.gallery-image-add', function(e) {

            var $el = $( this );
            e.preventDefault();

            if (file_frame) file_frame.close();

            file_frame = wp.media.frames.file_frame = wp.media({
                title: $(this).data('uploader-title'),
                button: {
                    text: $(this).data('uploader-button-text')
                },
                multiple: false
            });

            file_frame.on('select', function() {
                var selection = file_frame.state().get('selection');

                selection.map(function(attachment, i) {
                    var url;
                    attachment = file_frame.state().get('selection').first().toJSON();

                    if (attachment.sizes['thumbnail'].url) {
                        url = attachment.sizes['thumbnail'].url;
                    } else if (attachment.sizes['thumbnail'].url) {
                        url = attachment.sizes['thumbnail'].url;
                    } else {
                        url = attachment.icon;
                    }

                    var $container = $el.closest( '.es-profile-photo-wrap' );

                    if ($container.find('.es-attachment-logo').length) {
                        $container.find('.es-attachment-logo').html("<li><input type='hidden' name='" + $container.find('.es-attachment-logo').data('name') + "' value='" + attachment.id + "'>" +
                            "<div class='image-preview-wrap'><a href='#' class='remove-image'><i class='fa fa-times-circle' aria-hidden='true'></i></a>" +
                            "<img src='" + url + "' class='image-preview'></div></li>");
                    } else {
                        $('#es-media-list').html("<li><input type='hidden' name='es_user[profile_attachment_id]' value='" + attachment.id + "'>" +
                            "<div class='image-preview-wrap'><a href='#' class='remove-image'><i class='fa fa-times-circle' aria-hidden='true'></i></a>" +
                            "<img src='" + url + "' class='image-preview'></div></li>");
                    }
                });
            });

            file_frame.open();
        });

        var $elementorMode = $( '#elementor-switch-mode-input' );
        if ( $elementorMode.length && $elementorMode.val() ) {
            var interval;

            interval = setInterval( function() {
                if ( ! $elementorMode.val() ) {
                    var content;
                    const editor = tinyMCE.get( 'alternative_description' );
                    if ( null !== editor && false === editor.hidden ) {
                        // Ok, the active tab is Visual
                        content = editor.getContent();
                    } else {
                        // The active tab is HTML, so just query the textarea
                        content = $('#alternative_description' ).val();
                    }

                    const wp_editor = tinyMCE.get( 'content' );

                    if ( null !== wp_editor && false === wp_editor.hidden ) {
                        // Ok, the active tab is Visual
                        wp_editor.setContent( content );
                    } else {
                        // The active tab is HTML, so just query the textarea
                        $( '#content' ).val( content );
                    }

                    clearInterval( interval );
                }
            }, 500 );
        }
    });
})(jQuery);
