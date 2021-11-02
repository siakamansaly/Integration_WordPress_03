<?php

/**
 * @file functions.php
 *
 * Implements functions for plugin.
 */

if ( ! function_exists( 'es_register_image_sizes' ) ) {

    /**
     * @return void
     */
    function es_register_image_sizes() {
        $sizes = Estatik::get_image_sizes();

        foreach ( $sizes as $name => $data ) {
            if ( ! has_image_size( $name ) ) {
                add_image_size( $name, $data[0], $data[1], $data[2] );
            }
        }
    }
}
add_action( 'after_setup_theme', 'es_register_image_sizes', 5 );

/**
 * Return current URL.
 *
 * @return string
 */
function es_get_current_url() {
    return set_url_scheme( '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
}

/**
 * @return string
 */
function es_get_demo_finish_url() {
    return 'admin.php?page=es_demo&step=finished';
}

/**
 * @return mixed
 */
function es_admin_buyers_uri() {
	return apply_filters( 'es_admin_buyers_uri', 'users.php?role=' . Es_Buyer::get_role_name() );
}

/**
 * Return estatik plugin logo path.
 *
 * @return mixed
 */
function es_logo_url() {
    return apply_filters( 'es_logo_url', ES_PLUGIN_URL . '/admin/assets/images/logo.png' );
}

/**
 * Method for getting locale with WPML support.
 *
 * @return mixed
 */
function es_get_locale() {

    // Polylang integration.
    if ( ! empty( $_POST['post_lang_choice'] ) ) {
        return sanitize_text_field( $_POST['post_lang_choice'] );
    }

    if ( ! empty( $_REQUEST['icl_post_language'] ) ) {
        return sanitize_text_field( $_REQUEST['icl_post_language'] );
    }

    if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
        return ICL_LANGUAGE_CODE;
    }

    $lang = ! empty( $_GET['lang'] ) ? sanitize_text_field( $_GET['lang'] ) : str_replace( '_', '-', get_locale() );

    return apply_filters( 'es_get_locale', $lang );
}

/**
 * @return mixed|void
 */
function es_get_gmap_locale() {
    $locale = es_get_locale();
    $locale = str_replace( '-', '_', $locale );
//    $locale_explode = explode( '-', $locale );
//    $locale = ! empty( $locale_explode[0] ) ? $locale_explode[0] : $locale;
    return apply_filters( 'es_get_gmap_locale', $locale );
}

/**
 * Return array of classes for list.
 *
 * @return mixed
 */
function es_get_list_classes() {
    global $es_settings;

    $list[] = 'es-listing';
    $list[] = 'es-layout-' . $es_settings->listing_layout;
    $list[] = 'hentry';

    $template = get_option( 'template' );

    if ( 'rectangulum' == $template ) {
        $list[] = 'entry-content';
    }

    return apply_filters( 'es_get_list_classes', $list);
}

/**
 * Display list class string.
 *
 * @return void
 */
function es_the_list_classes() {
    $classes = es_get_list_classes();
    echo ! empty( $classes ) ? implode(' ', $classes) : '';
}

/**
 * Return create property page uri.
 *
 * @return mixed
 */
function es_admin_property_add_uri() {
    return apply_filters( 'es_admin_property_add_url', 'post-new.php?post_type=' . Es_Property::get_post_type_name() );
}

/**
 * Return create property page uri.
 *
 * @return mixed
 */
function es_admin_fields_builder_uri() {
    return apply_filters( 'es_admin_property_fields_builder_uri', 'admin.php?page=es_fbuilder' );
}

/**
 * Return data manager page uri.
 *
 * @return mixed
 */
function es_admin_data_manager_uri() {
    return apply_filters( 'es_admin_data_manager_uri', 'admin.php?page=es_data_manager' );
}

/**
 * Return data manager page uri.
 *
 * @return mixed
 */
function es_admin_dashboard_uri() {
    return apply_filters( 'es_admin_dashboard_uri', 'admin.php?page=es_dashboard' );
}

/**
 * Return data manager page uri.
 *
 * @return mixed
 */
function es_admin_settings_uri() {
    return apply_filters( 'es_admin_settings_uri', 'admin.php?page=es_settings' );
}

/**
 * Return Estatik admin listings URI.
 *
 * @return string
 */
function es_admin_property_list_uri() {
    return apply_filters( 'es_admin_property_list_uri', 'edit.php?post_type=' . Es_Property::get_post_type_name() );
}

/**
 * @retrun void
 */
function es_migration_set_executed() {
    update_option( 'es_migration_already_executed', true );
}

/**
 * @return bool
 */
function es_migration_already_executed() {
    return apply_filters( 'es_migration_already_executed', get_option( 'es_migration_already_executed' ) );
}

/**
 * @return bool
 */
function es_demo_executed() {
	return apply_filters( 'es_demo_already_executed', get_option( 'es_demo_executed' ) );
}

/**
 * @return string
 */
function es_listings_link() {
    global $es_settings;

    return $es_settings->all_listings_page_id ? get_permalink( $es_settings->all_listings_page_id ) : get_post_type_archive_link( 'properties' );
}

/**
 * @return array
 */
function es_need_migrate() {
    return Es_Property_Migration::get_prop_ids();
}

/**
 * Return estatik logo markup.
 *
 * @return string;
 */
function es_get_logo() {
    ob_start();

    do_action( 'es_before__logo' );
    do_action( 'es_before_logo' );
    echo "<div class='es-logo clearfix'><img src='" . es_logo_url() . "'><br>
            <span class='es-version'>" . __( 'Ver', 'es-plugin' ) . ". " . Estatik::getVersion() .  "</span></div>";
    do_action( 'es_after_logo');

    return ob_get_clean();
}

/**
 * Return property field value.
 *
 * @param $field
 * @param int $post
 * @return mixed|null
 */
function es_get_the_property_field( $field, $post = 0 ) {
    $post = get_post( $post );

    if ( ! empty( $post->ID ) ) {
        $es_property = es_get_property( $post->ID );

        $finfo = Es_Property::get_field_info( $field );

        if ( ! empty( $finfo['system_type'] ) && 'taxonomy' == $finfo['system_type'] && ! empty( $finfo['loop_callback'] ) ) {
            $result = call_user_func_array( $finfo['loop_callback']['callback'], $finfo['loop_callback']['args'] );
        } else if ( ! empty( $finfo['system'] ) ) {
            $result = $es_property->get_entity()->{$field};
        } else {
            $result = $es_property->{$field};
        }

        $formatter = ! empty( $finfo['formatter'] ) ? $finfo['formatter'] : null;

        if ( is_numeric( $result ) && ! empty( $finfo['type'] ) && $finfo['type'] != 'text' && $formatter != 'area' ) {
            $result = $result == (int) $result ? (int) $result : $result;
        }

        return apply_filters( 'es_get_the_' . $field, $result );
    }

    return null;
}

/**
 * Filter value for date added field.
 *
 * @return string
 */
function es_get_the_date_added() {
    return es_the_date('', '', false );
}
add_filter( 'es_get_the_date_added', 'es_get_the_date_added', 10 );

/**
 * Render property field.
 *
 * @param $field
 * @param string $before
 * @param string $after
 */
function es_the_property_field( $field, $before = '', $after = '' ) {
    $result = es_get_the_property_field( $field );

    echo ! empty( $result ) ? $before . $result . $after : null;
}

/**
 * Return bathrooms formatted string.
 *
 * @param int $post
 * @return null|string
 */
function es_get_the_formatted_bathrooms( $post = 0 ) {
    $value = es_get_the_property_field( 'bathrooms', $post );

    if ( ! empty( $value ) ) {
        // _n function doesn't work :(
        return $value == 1 ? sprintf( __( '%g bath', 'es-plugin' ), $value  ) :  sprintf( __( '%g baths', 'es-plugin' ), $value );
    }
}

/**
 * Display formatter bath string.
 *
 * @see es_get_the_formatted_bathrooms()
 *
 * @param string $before
 * @param string $after
 * @param bool $display_empty
 *
 * @return void|null|string
 */
function es_the_formatted_bathrooms( $before = '', $after = '', $display_empty = false ) {
    $value = es_get_the_formatted_bathrooms();

    if ( $display_empty || ( ! empty( $value ) ) ) {
        echo $before . $value . $after;
    }
}

/**
 * Return beds formatted string.
 *
 * @param int $post
 * @return null|string
 */
function es_get_the_formatted_bedrooms( $post = 0 ) {
    $value = es_get_the_property_field( 'bedrooms', $post );

    if ( ! empty( $value ) ) {
        // _n function doesn't work :(
        return $value == 1 ? sprintf( __( '%g bed', 'es-plugin' ), $value  ) :  sprintf( __( '%g beds', 'es-plugin' ), $value );
    }
}

/**
 * Display formatter beds string.
 *
 * @see es_get_the_formatted_bathrooms()
 *
 * @param string $before
 * @param string $after
 * @param bool $display_empty
 *
 * @return void|null|string
 */
function es_the_formatted_bedrooms( $before = '', $after = '', $display_empty = false ) {
    $value = es_get_the_formatted_bedrooms();

    if ( $display_empty || ( ! empty( $value ) ) ) {
        echo $before . $value . $after;
    }
}

/**
 * Display property title using {title_address} setting.
 *
 * @param string $before
 * @param string $after
 */
function es_the_title( $before = '', $after = '' ) {
    $result = es_get_the_title();
    echo ! empty( $result ) ? $before . $result . $after : null;
}

/**
 * Return address or title of the property using {title_address} setting.
 *
 * @param int $post
 * @return string
 */
function es_get_the_title( $post = 0 ) {
    global $es_settings;

    if ( $es_settings->title_address == 'address' ) {
        return es_get_the_property_field( 'address', $post );
    } else {
        return get_the_title( $post );
    }
}


/**
 *
 * the_title filter.
 * @param $title
 * @param $id
 * @return string
 */
function es_the_title_filter( $title, $id = null ) {
    global $es_settings;
    $post = get_post( $id );

    // It's correct condition! don't use is_singular!
    if ( $post && $post->post_type == 'properties' ) {
        if ( $es_settings->title_address == 'address' ) {
            return es_get_the_property_field( 'address', $post );
        }
    }

    return $title;
}
add_filter( 'the_title', 'es_the_title_filter', 10, 2 );

/**
 * Display property address string.
 *
 * @param string $before
 * @param string $after
 */
function es_the_address( $before = '', $after = '' ) {
    global $es_settings;

    $result = es_get_the_property_field( 'address' );
    echo ! empty( $result ) && $es_settings->show_address ? $before . $result . $after : null;
}

/**
 * Display price using currency settings.
 *
 * @param string $before
 * @param string $after
 * @param bool $echo
 * @return null|string
 */
function es_the_formatted_price( $before = '', $after = '', $echo = true ) {

    /** @var Es_Settings_Container $es_settings */
    global $es_settings;

    // Get property price.
    $price = es_get_the_property_field( 'price' );
    $call = es_get_the_property_field( 'call_for_price' );

    if ( $call && $es_settings->show_price ) {
        $result = '<span class="es-price">' . __( 'Call for price', 'es-plugin' ) . '</span>';

        $result = $before . apply_filters( 'es_the_formatted_price', $result, $price, $call ) . $after;

        if ( ! $echo ) {
            return $result;
        }

        echo $result;
        return;
    }

    // Get position of the currency.
    $position = $es_settings->currency_position;
    // Get currency name using currency code.
    $currency = $es_settings->get_label( 'currency', $es_settings->currency );
    // Get price format.
    $format = $es_settings->price_format;

    $price_temp = ! $price ? 0 : $price;

    $sup = ! empty( $format[0] ) ? $format[0] : null;
    $dec = ! empty( $format[1] ) ? $format[1] : null;

	$dec_num = $sup == ' ' || $sup == ',' || $sup == '.' ? 0 : 2;
    $dec_num = $format == ',.' || $format == '.,' ? 2 : $dec_num;

    if ( $currency == 'RUB' ) {
        $currency = '<i class="fa fa-rub" aria-hidden="true"></i>';
    }

    $price_temp = floatval( $price_temp );
    $price_temp = number_format( $price_temp, $dec_num, $dec, $sup );
    $price_temp = $position == 'after' ? $price_temp . ' ' . $currency : $currency . ' ' . $price_temp;

    $result = '<span class="es-price">' . $price_temp . '</span>';

    $formetted = ! empty( $price ) && $es_settings->show_price ?
        $before . apply_filters( 'es_the_formatted_price', $result, $price_temp ) . $after : null;

    if ( $echo ) {
        echo $formetted;
    } else {
        return $formetted;
    }
}

/**
 * @param $result
 * @param $formatter
 * @param null $unit
 *
 * @return null|string
 */
function es_format_field( $result, $formatter, $unit = null ) {

    global $es_settings;

	switch( $formatter ) {
		case 'price':
			if ( ! $result && ! strlen( $result ) ) break;

			// Get position of the currency.
			$position = $es_settings->currency_position;
			// Get currency name using currency code.
			$currency = $unit ? $unit : $es_settings->get_label( 'currency', $es_settings->currency );
			// Get price format.
			$format = $es_settings->price_format;

			$price_temp = ! $result ? 0 : $result;

			$sup = ! empty( $format[0] ) ? $format[0] : null;
			$dec = ! empty( $format[1] ) ? $format[1] : null;

			$dec_num = $sup == ' ' || $sup == ',' || $sup == '.' ? 0 : 2;
			$dec_num = $format == ',.' || $format == '.,' ? 2 : $dec_num;

			if ( $currency == 'RUB' ) {
				$currency = '<i class="fa fa-rub" aria-hidden="true"></i>';
			}

			$price_temp = floatval( $price_temp );
			$price_temp = number_format( $price_temp, $dec_num, $dec, $sup );

			return $position == 'after' ? $price_temp . ' ' . $currency : $currency . ' ' . $price_temp;

			break;

		case 'area':
			if ( ! $result && ! strlen( $result ) ) break;

			$unit = $unit ? $unit : $es_settings->unit;
			$unit = $unit ? $es_settings->get_label( 'unit', $unit ) : null;

			return  $result . ' ' . $unit;

			break;

		case 'url':
			if ( $result ) {

				if ( is_array( $result ) ) {
					$url = ! empty( $result['url'] ) ? $result['url'] : null;
					$label = ! empty( $result['label'] ) ? $result['label'] : $url;
				}

				if ( is_string( $result ) ) {
					$url = $result;
					$label = $result;
				}

				if ( ! empty( $url ) && ! empty( $label ) ) {
					return "<a class='es-url-link' target='_blank' href='" . esc_url( $url ) . "'>{$label}</a>";
				} else {
					return null;
				}
			} else {
				return null;
			}

			break;

        case 'address_component':
        case 'location':
            $address_component = ES_Address_Components::get_component( $result );

            if ( $address_component ) {
                return $address_component->long_name;
            }
            break;

	}

	return apply_filters( 'es_get_formatted_field', $result, $formatter );
}

/**
 * @param $field
 * @param $formatter
 *
 * @return string
 */
function es_get_the_formatted_field( $field, $formatter, $value = null ) {

    /** @var Es_Settings_Container $es_settings */
    global $es_settings;

    $result = es_get_the_property_field( $field );

    switch( $formatter ) {
        case 'price':
            if ( ! $result && ! strlen( $result ) ) break;

            // Get position of the currency.
            $position = $es_settings->currency_position;
            // Get currency name using currency code.
            $currency = $es_settings->get_label( 'currency', $es_settings->currency );
            // Get price format.
            $format = $es_settings->price_format;

            $price_temp = ! $result ? 0 : $result;

            $sup = ! empty( $format[0] ) ? $format[0] : null;
            $dec = ! empty( $format[1] ) ? $format[1] : null;

	        $dec_num = $sup == ' ' || $sup == ',' || $sup == '.' ? 0 : 2;
	        $dec_num = $format == ',.' || $format == '.,' ? 2 : $dec_num;

            if ( $currency == 'RUB' ) {
                $currency = '<i class="fa fa-rub" aria-hidden="true"></i>';
            }

            $price_temp = floatval( $price_temp );
            $price_temp = number_format( $price_temp, $dec_num, $dec, $sup );

            return $position == 'after' ? $price_temp . ' ' . $currency : $currency . ' ' . $price_temp;

            break;

        case 'bedrooms':
            if ( $result ) {
                $unit = $result > 1 ? __( '%g beds', 'es-plugin' ) : __( '%g bed', 'es-plugin' );
                $result =  sprintf( $unit, $result );
            }
            break;

        case 'bathrooms':
            if ( $result ) {
                $unit = $result > 1 ? __( '%g baths', 'es-plugin' ) : __( '%g bath', 'es-plugin' );
                $result = sprintf( $unit, $result );
            }
            break;

        case 'area':
	        if ( ! $result && ! strlen( $result ) ) break;
            $es_property = es_get_property( null );
            $fields = $es_property::get_fields();

            $unit = ! empty( $fields[ $field ]['units'] ) ? es_get_the_property_field( $fields[ $field ]['units'] ) : null;
            $unit = $unit ? $unit : $es_settings->unit;
            $unit = $unit ? $es_settings->get_label( 'unit', $unit ) : null;

            return  $result . ' ' . $unit;

            break;

        case 'url':
            if ( $result ) {

                if ( is_array( $result ) ) {
                    $url = ! empty( $result['url'] ) ? $result['url'] : null;
                    $label = ! empty( $result['label'] ) ? $result['label'] : $url;
                }

                if ( is_string( $result ) ) {
                    $url = $result;
                    $label = $result;
                }

                if ( ! empty( $url ) && ! empty( $label ) ) {
                    return "<a class='es-url-link es-url-link-{$field}' target='_blank' href='" . esc_url( $url ) . "'>{$label}</a>";
                } else {
                    return null;
                }
            } else {
                return null;
            }

            break;

	    case 'file':
		    $entity_field_info = Es_Property::get_field_info( $field );

		    if ( $result && ( $attachment = wp_get_attachment_url( $result ) ) ) {
			    $finfo = pathinfo( $attachment );

			    if ( ! empty( $entity_field_info['show_thumbnail'] ) ) {
				    if ( ! function_exists( 'file_is_valid_image' ) ) {
					    require_once( ABSPATH . 'wp-admin/includes/image.php' );
				    }

				    if ( wp_attachment_is_image( $result ) ) {
					    $file = '<a href="' . $attachment . '" class="js-magnific-gallery">' . wp_get_attachment_image( $result, 'es-image-size-archive' ) . '</a>';
				    } else {
					    $file = '<a href="' . $attachment . '" target="_blank">' . es_get_file_icon( $finfo['basename'] ) . '</a>';
				    }

				    return  array( 'markup' => sprintf("<li class='es-file__wrap'>
                                <div class='es-file__content'>%s</div>
                                <span class='es-file__label'><b>%s</b></span>
                            </li>",$file, ! empty( $entity_field_info['label'] ) ? $entity_field_info['label']  : null ) );
			    } else {
				    $icon = es_get_file_icon( $finfo['basename'] );
				    $icon = $icon ? $icon . ' ' : null;
				    return $icon . '<a href="' . $attachment . '" target="_blank">' .$finfo['basename'] . '</a>';
			    }
		    }
		    break;

        case 'locality':
        case 'location':
            $entity_field_info = Es_Property::get_field_info( $field );
            if ( ! empty( $entity_field_info['components_types'] ) ) {
                foreach ( $entity_field_info['components_types'] as $type ) {
                    if ( $component = ES_Address_Components::get_property_component( get_the_ID(), $type ) ) {
                        $result = $component->long_name;
                        break;
                    }
                }
            }

            break;
    }

    return apply_filters( 'es_get_the_formatted_field', $result, $field, $formatter );
}

/**
 * Return property categories.
 *
 * @param $before
 * @param $sep
 * @param $after
 *
 * @return string
 */
function es_the_categories( $before = '', $sep = ', ', $after = '', $echo = true ) {
	ob_start();
	the_terms( 0, 'es_category', $before, $sep, $after );
	$result = ob_get_clean();

	if ( $echo ) {
		echo $result;
	} else {
		return $result;
	}
}

/**
 * @return mixed
 */
function es_get_standard_label_names() {
    return  apply_filters( 'es_install_standard_labels', array(
        '#00cbf0' => __( 'Featured', 'es-plugin' ),
        '#ff9600' => __( 'Hot', 'es-plugin'),
        '#2bbe0e' => __( 'Open House', 'es-plugin' ),
        '#9e9e9e' => __( 'Foreclosure', 'es-plugin' ),
    ) );
}

function es_get_default_label_color() {
	return apply_filters( 'es_default_label_color', '#9e9e9e' );
}

/**
 * @param $term_id
 * @param string $color
 * @return mixed|string
 */
function es_get_the_label_color(  $term_id, $color = '#9e9e9e' ) {
    $meta = get_term_meta( $term_id, 'es_color', true );
    return ! empty( $meta ) ? get_term_meta( $term_id, 'es_color', true ) : $color;
}

/**
 * Return property status list.
 *
 * @param string $before
 * @param string $sep
 * @param string $after
 * @param bool $echo
 * @return string
 */
function es_the_status_list( $before = '', $sep = ', ', $after = '', $echo = true ) {
    ob_start();
    the_terms( 0, 'es_status', $before, $sep, $after );
    $result = ob_get_clean();

    if ( $echo ) {
        echo $result;
    } else {
        return $result;
    }
}

/**
 * @param int $post
 * @param null $more_link_text
 * @param bool $strip_teaser
 * @return string
 */
function es_get_the_content( $post = 0, $more_link_text = null, $strip_teaser = false ) {
    $post = get_post( $post );
    $property = es_get_property( $post->ID );
    $elementor_editor_mode = es_is_elementor_builder_enabled( $post->ID );
    $divi_builder = function_exists( 'et_pb_is_pagebuilder_used' ) && et_pb_is_pagebuilder_used( $post->ID );
    return $elementor_editor_mode || $divi_builder ? $property->alternative_description : get_the_content( $more_link_text, $strip_teaser, $post );
}

/**
 * Overridden the_content function.
 * For now the_content function uses for execute [es_single] shortcode.
 * Use this function instead of the_content.
 *
 * @param null $more_link_text
 * @param bool $strip_teaser
 */
function es_the_content( $more_link_text = null, $strip_teaser = false ) {
    $content = es_get_the_content( $more_link_text, $strip_teaser );

    global $es_settings;

    if ( $es_settings->is_tags_enabled && $es_settings->is_tags_clickable ) {
        $tags = get_terms( array(
            'taxonomy' => 'es_tags',
            'hide_empty' => true,
            'fields' => 'id=>name',
        ) );
        $replace = array();

        if ( ! empty( $tags ) ) {
            foreach ( $tags as $id => $tag ) {
                $replace[ $tag ] = "<a href='" . get_term_link( $id, 'es_tags' ) . "'>{$tag}</a>";
            }
        }

        if ( $replace ) {
            $content = strtr( $content, $replace );
        }
    }

    /**
     * Filters the post content.
     *
     * @since 0.71
     *
     * @param string $content Content of the current post.
     */
    $content = apply_filters( 'es_the_content', $content );
    $content = str_replace( ']]>', ']]&gt;', $content );

    echo do_shortcode( $content );
}

add_filter( 'es_the_content', 'wptexturize'                       );
add_filter( 'es_the_content', 'convert_smilies',               20 );
add_filter( 'es_the_content', 'wpautop'                           );
add_filter( 'es_the_content', 'shortcode_unautop'                 );
add_filter( 'es_the_content', 'prepend_attachment'                );

/**
 * @param $value
 * @param $field
 * @param $single
 * @param $entity
 * @return string
 */
//function es_get_property_description( $value, $field, $single, $entity ) {
//    if ( $entity instanceof Es_Property && $entity->getID() && 'alternative_description' == $field ) {
//        $post = $entity->get_entity();
//        $post_content = stristr( $post->post_content, '[es_single' ) ? '' : $post->post_content;
//        $value = $value ? $value : $post_content;
//    }
//
//    return $value;
//}
//add_filter( 'es_entity_get_field_value', 'es_get_property_description', 10, 4 );

/**
 * Return property status list.
 *
 * @param $before
 * @param $sep
 * @param $after
 * @return string
 */
function es_the_rent_period( $before = '', $sep = ', ', $after = '', $echo = true ) {
    ob_start();
    the_terms( 0, 'es_rent_period', $before, $sep, $after );
    $result = ob_get_clean();

    if ( $echo ) {
        echo $result;
    } else {
        return $result;
    }
}

/**
 * Return property status list.
 *
 * @param $before
 * @param $sep
 * @param $after
 */
function es_the_amenities( $before = '', $sep = ', ', $after = '' ) {
    the_terms( 0, 'es_amenities', $before, $sep, $after );
}

/**
 * Return list of terms of amenities taxonomy.
 *
 * @return array|false|WP_Error
 */
function es_get_the_amenities( $post = 0 ) {
    return get_the_terms( $post, 'es_amenities' );
}

/**
 * @param int $post
 * @return array|false|WP_Error
 */
function es_get_the_features( $post = 0 ) {
    return get_the_terms( $post, 'es_feature' );
}

/**
 * Display list of property types.
 *
 * @param string $before
 * @param string $sep
 * @param string $after
 * @param bool $echo
 * @return void|string
 */
function es_the_types( $before = '', $sep = ', ', $after = '', $echo = true ) {
    ob_start();
    the_terms( 0, 'es_type', $before, $sep, $after );
    $result = ob_get_clean();

    if ( $echo ) {
        echo $result;
    } else {
        return $result;
    }
}

/**
 * Render property formatted area field with units.
 *
 * @param string $before
 * @param string $after
 * @param bool $echo
 *
 * @return void|string
 */
function es_the_formatted_area( $before = '', $after = '', $echo = true ) {
    /** @var Es_Settings_Container $es_settings */
    global $es_settings;

    $es_property = es_get_property( null );
    $fields = $es_property::get_fields();

    $result = es_get_the_property_field( 'area' );
    $unit = ! empty( $fields['area']['units'] ) ? es_get_the_property_field( $fields['area']['units'] ) : null;
    $unit = $unit ? $unit : $es_settings->unit;
    $unit = $unit ? $es_settings->get_label( 'unit', $unit) : null;

    $result = ! empty( $result ) ?
        $before . apply_filters( 'es_the_formatted_area', $result . ' ' . $unit, $result ) . $after : null;

    if ( $echo ) {
        echo $result;
    } else {
        return $result;
    }
}

/**
 * @deprecated since 3.11.0
 * @return int
 */
function es_get_default_thumbnail_id() {
    global $es_settings;

    $attachment_id = $es_settings->thumbnail_attachment_id;

    if ( ! $attachment_id ) {
        if ( ! function_exists( 'wp_handle_upload' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }

        if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
        }

        if ( ! function_exists( 'media_handle_upload' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/media.php' );
        }

        $thumbnail = ES_PLUGIN_URL . 'assets/images/thumbnail.png';
        $upload_dir = wp_upload_dir();
        $file['name'] = basename( $thumbnail );
        $file['tmp_name'] = download_url( $thumbnail );

        $file = wp_handle_sideload( $file, array( 'test_form' => false ) );

        if ( empty( $file['error'] ) ) {
            $wp_filetype = wp_check_filetype( basename( $file['file'] ), null );

            $attachment = array(
                'guid' => $upload_dir['baseurl'] . ES_DS . _wp_relative_upload_path( $file['file'] ),
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename( $file['file'] ) ),
                'post_content' => '',
                'post_status' => 'inherit'
            );

            $attachment_id = wp_insert_attachment( $attachment, $file['file'] );

            $attach_data = wp_generate_attachment_metadata( $attachment_id,  get_attached_file( $attachment_id ) );
            wp_update_attachment_metadata( $attachment_id,  $attach_data );

            $es_settings->saveOne( 'thumbnail_attachment_id', $attachment_id );
            $attachment_id = $es_settings->thumbnail_attachment_id;
        }
    }

    return $attachment_id;
}

/**
 * @param string $size
 * @return string
 */
function es_get_default_thumbnail( $size = 'thumbnail' ) {
    return apply_filters( 'es_get_default_thumbnail', "<img src='" . es_get_default_thumbnail_url() . "'/>" );
}

/**
 * @param string $size
 * @return string
 */
function es_get_default_thumbnail_url( $size = 'thumbnail' ) {
    return apply_filters( 'es_get_default_thumbnail_url', ES_PLUGIN_URL . 'assets/images/thumbnail.png' );
}

/**
 * Render property formatted lot size field with units.
 *
 * @param string $before
 * @param string $after
 * @param bool $echo
 * @return mixed|null|string|void
 */
function es_the_formatted_lot_size( $before = '', $after = '', $echo = true ) {
    /** @var Es_Settings_Container $es_settings */
    global $es_settings;

    $es_property = es_get_property( null );
    $fields = $es_property::get_fields();

    $result = es_get_the_property_field( 'lot_size' );
    $unit = ! empty( $fields['lot_size']['units'] ) ? es_get_the_property_field( $fields['lot_size']['units'] ) : null;
    $unit = $unit ? $unit : $es_settings->unit;
    $unit = $unit ? $es_settings->get_label( 'unit', $unit ) : null;

    $result = ! empty( $result ) ?
        $before . apply_filters( 'es_the_formatted_lot_size', $result . ' ' . $unit, $result ) . $after : null;

    if ( $echo ) {
        echo $result;
    } else {
        return $result;
    }
}

/**
 * Return property map block.
 *
 * @return void
 */
function es_the_map() {
    $es_property = es_get_property( get_the_ID() );

    $data = ! empty( $es_property->latitude ) && ! empty( $es_property->longitude ) ?
        "data-lat='{$es_property->latitude}' data-lon='{$es_property->longitude}'" : null;

	if ( $data ) {
        echo apply_filters( 'es_the_map', '<div id="es-google-map" ' . $data . ' style="width:100%; height:300px;"></div>' );
    }
}

/**
 * Return property added date.
 *
 * @param string $before
 * @param string $after
 * @param bool $echo
 * @return string|void
 */
function es_the_date( $before = '', $after = '', $echo = true ) {
    global $es_settings;

    if ( $es_settings->date_added ) {
        if ( $echo ) {
            the_date( $es_settings->date_format, $before, $after, $echo );
        } else {
            return $before . get_the_date( $es_settings->date_format ) . $after;
        }
    }
}

/**
 * Get property.
 *
 * @param $post_id
 * @return Es_Property
 */
function es_get_property( $post_id ) {
    return apply_filters( 'es_get_property', new Es_Property( $post_id ) );
}

/**
 * @param $from
 * @param $to
 * @param $value
 *
 * @return int
 */
function es_prepare_unit( $from, $to, $value ) {

    // OH. MY. GOD. Switch in the switch. Perfect.

    switch ( $from ) {

        case 'hectares':
            switch ( $to ) {
                case 'sq_ft':
                    $value = $value * 107639.104;
                    break;

                case 'sq_m':
                    $value = $value * 10000;
                    break;

                case 'acres':
                    $value = $value * 2.4710538146717;
                    break;
            }
            break;

        case 'sq_ft':
            switch ( $to ) {
                case 'hectares':
                    $value = $value / 107639.104;
                    break;

                case 'sq_m':
                    $value = $value / 10;
                    break;

                case 'acres':
                    $value = $value / 43560;
                    break;
            }
            break;

        case 'sq_m':
            switch ( $to ) {
                case 'hectares':
                    $value = $value / 10000;
                    break;

                case 'sq_ft':
                    $value = $value * 10;
                    break;

                case 'acres':
                    $value = $value / 4046.8564300507887;
                    break;
            }
            break;

        case 'acres':
            switch ( $to ) {
                case 'hectares':
                    $value = $value / 2.4710538146717;
                    break;

                case 'sq_ft':
                    $value = $value * 43560;
                    break;

                case 'sq_m':
                    $value = $value * 4046.8564300507887;
                    break;
            }
            break;

        default:
            $value = false;
    }

    return apply_filters( 'es_prepare_unit', $value, $from, $to, $value );
}

/**
 * Return HTML based content for ajax action for calculate units.
 *
 * @return void
 */
function es_ajax_calculate_units() {

    if ( check_ajax_referer( 'es_admin_nonce', 'nonce' ) && current_user_can( 'es_view_calculated_units' ) ) {

	    if ( ! empty( $_POST['unit'] ) && ! empty( $_POST['val'] ) ) {
		    /** @var $es_settings Es_Settings_Container */
		    global $es_settings; $content = null;

		    $template = "<b>{unit}: </b>{value}</br>";

		    foreach ( $es_settings::get_setting_values('unit') as $key => $setting_value ) {
			    $unit = es_prepare_unit( sanitize_key( $_POST['unit'] ), $key, sanitize_text_field( $_POST['val'] ) );

			    if ( $unit === false ) continue;

			    $content .= strtr( $template, array(
				    '{value}' => $unit,
				    '{unit}' => $es_settings->get_label( 'unit', $key )
			    ) );
		    }

		    wp_die( json_encode( array(
			    'status' => true,
			    'content' => $content,
		    ) ) );
	    }
    }
}

add_action( 'wp_ajax_es_calculate_units', 'es_ajax_calculate_units' );

/**

 * Render property main thumbnail.
 *
 * @param string $size
 * @param bool $icon
 */
function es_the_post_thumbnail( $size = 'thumbnail', $icon = false, $post = 0 ) {

	if ( $post ) {
		$post = get_post( $post );
	} else {
		global $post;
	}

	$property = es_get_property( $post->ID );
	$images = $property->gallery;
	$icon = $icon ? es_get_default_thumbnail( $size ) : null;

    echo ! empty( $images[0] ) && ! is_wp_error($images[0]) ? wp_get_attachment_image( $images[0], $size ) : $icon;
}

/**
 * @param string $size
 * @param int $post
 */
function es_get_the_post_thumbnail_url( $size = 'thumbnail', $post = 0 ) {
	$post = get_post( $post );

	$property = es_get_property( $post->ID );
	$images = $property->gallery;

	$thumbnail = get_the_post_thumbnail_url( $post, $size );
	$thumbnail_gallery = ! empty( $images[0] ) ? wp_get_attachment_image_url( $images[0], $size ) : '';
    $img = $thumbnail ? $thumbnail : $thumbnail_gallery;

    $icon = es_get_default_thumbnail_url( $size );
	return $img ? $img : $icon;
}

/**
 * Render pagination fir custom WP Query loop.
 *
 * @param $query
 * @param array $args
 *
 * @return string
 */
function es_the_pagination( $query, $args = array() ) {
	$navigation = null;

	if ( ! empty( $query->properties_loop_identifier ) ) {
		$format = '?paged-' . $query->properties_loop_identifier . '=%#%';
		$page_num = ! empty( $_GET[ 'paged-' . $query->properties_loop_identifier ] ) ? $_GET[ 'paged-' . $query->properties_loop_identifier ] : 1;
	} else {
		$format = '?paged=%#%';
		if ( get_query_var( 'paged' ) ) {
			$page_num = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$page_num = get_query_var( 'page' );
		} else {
			$page_num = 1;
		}
	}

	$page_num = intval( $page_num );

	$args = wp_parse_args( $args, array(
		'format'  => $format,
		'show_all'           => false,
		'end_size'           => 2,
		'mid_size'           => 2,
		'screen_reader_text' => ' ',
		'total'              => $query->max_num_pages,
		'current' => $page_num,
	) );

	$args = apply_filters( 'es_the_pagination_args', $args );

	$links = paginate_links( $args );

	if ( $links ) {
		$navigation = _navigation_markup( $links, 'pagination', $args['screen_reader_text'] );
	}

	return $navigation;
}

/**
 * Return file icon markup.
 *
 * @param $url
 * @return string Font Awesome icon markup.
 */
function es_get_file_icon( $url ) {
	$finfo = pathinfo( $url );
	$icon = null;

	if ( ! empty( $finfo['extension'] ) ) {
		switch ( $finfo['extension'] ) {
			case 'pdf':
				$class = 'fa-file-pdf-o';
				break;

			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':
			case 'bmp':
				$class = 'fa-file-image-o';
				break;

			case 'txt':
			case 'doc':
			case 'docx':
				$class = 'fa-file-text-o';
				break;

			case 'xls':
				$class = 'fa-file-excel-o';
				break;

			default:
				$class = 'fa-file-o';
		}

		$icon = '<i class="fa %s" aria-hidden="true"></i>';

		$icon = sprintf( $icon, $class );
	}

	return apply_filters( 'es_get_file_econ', $icon, $url, $finfo );
}

/**
 * Render google captcha function.
 *
 * @param string $action
 *
 * @return void
 */
function es_render_recaptcha( $action = 'register' ) {
	global $es_settings;
	$lang = es_get_locale();
	$recaptcha_version = $es_settings->recaptcha_version;
	$siteKey = $es_settings->recaptcha_site_key;
	$uid = uniqid();

	$url = 'https://www.google.com/recaptcha/api.js';
	$args = array( 'hl' => $lang );

	if ( empty( $siteKey ) ) return;

    if ( 'v3' == $recaptcha_version ) {
        $args['render'] = $siteKey; ?>
        <input type="hidden" name="g-recaptcha-response" id="recaptchaResponse-<?php echo $uid; ?>"/>
        <?php
    } else if ( 'v2' == $recaptcha_version ) : ?>
        <div class="es-recaptcha-wrapper">
            <div class="g-recaptcha" data-sitekey="<?php echo $siteKey;?>"></div>
        </div>
    <?php endif;

    wp_enqueue_script( 'es-google-recaptcha', add_query_arg( $args, $url ) );

    if ( 'v3' == $recaptcha_version ) {
	    wp_add_inline_script( 'es-google-recaptcha', "
            grecaptcha.ready(function () {
                grecaptcha.execute('" . $siteKey . "', { action: '" . $action . "' }).then(function (token) {
                    var recaptchaResponse = document.getElementById('recaptchaResponse-" . $uid . "');
                    recaptchaResponse.value = token;
                });
            });
        " );
    }
}
add_action( 'es_recaptcha', 'es_render_recaptcha', 10, 1 );

/**
 * @param $secret
 *
 * @return bool
 */
function es_validate_recaptcha( $secret = null ) {
	$check = false;

	if ( isset( $_REQUEST['g-recaptcha-response'] ) ) {
		if ( ! empty( $_REQUEST['g-recaptcha-response'] ) ) {

			global $es_settings;

			$recaptcha_response = sanitize_text_field( $_REQUEST['g-recaptcha-response'] );
			$secret = $secret ? $secret : $es_settings->recaptcha_secret_key;

			$verifyResponse = wp_safe_remote_get( sprintf( 'https://www.google.com/recaptcha/api/siteverify?secret=%s&response=%s', $secret, $recaptcha_response ) );

			if ( ! empty( $verifyResponse['body'] ) ) {
				$responseData = json_decode( $verifyResponse['body'] );

				if ( ! empty( $responseData->success ) ) {
					$check = true;
				}
			}
		} else {
			$check = false;
		}
	} else {
		$check = true;
	}

	return $check;
}

/**
 * @param $template_path
 * @param string $context
 * @param null $deprecated
 *
 * @return string
 */
function es_locate_template( $template_path, $context = 'front', $deprecated = null ) {

	$find = array();
	$context = $context == 'front' ? ES_TEMPLATES : ES_ADMIN_TEMPLATES;
	$base = $template_path;

	$find[] = 'estatik/' . $template_path;
	$find[] = $context . $template_path;

	$template_path = locate_template( array_unique( $find ) );

	if ( ! $template_path ) {
		$template_path = $context . $base;
	}

	$_path = $deprecated ? apply_filters( $deprecated, $template_path ) : $template_path;

	return apply_filters( 'es_locate_template', $_path, $template_path, $context );
}

/**
 * Template path.
 *
 * @param $template_path
 * @param string $context
 * @param $deprecated string
 * @param array $args
 */
function es_load_template( $template_path, $context = 'front', $deprecated = null, $args = array() ) {

	global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;
    $temp_context = $context;

    extract( $args );
    include es_locate_template( $template_path, $temp_context, $deprecated );
}

/**
 * Fluh rewrite rules when slug for properties post type is changed.
 *
 * @return void
 */
function es_flush_rewrite_rules() {

    if ( get_option( 'es_need_flush' ) ) {
        flush_rewrite_rules();
        delete_option( 'es_need_flush' );
    }
}
add_action( 'init', 'es_flush_rewrite_rules' );

/**
 * @param WP_Admin_Bar $admin_bar
 */
function es_admin_bar_edit_property_link( $admin_bar ) {

    if ( is_singular( Es_Property::get_post_type_name() ) && current_user_can( 'edit_post', get_the_ID() ) ) {
        $admin_bar->add_menu( array(
            'id'    => 'edit-property',
            'title' => __( 'Edit property', 'es-plugin' ),
            'href'  => get_edit_post_link( get_the_ID() ),
            'meta'  => array(
                'title' => __( 'Edit property', 'es-plugin' ),
            ),
        ));
    }
}
add_action( 'admin_bar_menu', 'es_admin_bar_edit_property_link', 100 );

/**
 * Return wishlist instance.
 *
 * @return Es_Wishlist_Cookie|Es_Wishlist_User
 */
function es_get_wishlist_instance() {

    if ( is_user_logged_in() ) {
        $instance =  new Es_Wishlist_User( get_current_user_id() );
    } else {
        $instance = new Es_Wishlist_Cookie();
    }

    return apply_filters( 'es_get_wishlist_instance', $instance );
}

/**
 * @param $post_id
 *
 * @param bool $added
 *
 * @return string
 */
function es_wishlist_get_button( $post_id, $added = false ) {
    ob_start();
    es_wishlist_add_button( $post_id, $added );
    return ob_get_clean();
}

/**
 * @param $post_id
 */
function es_wishlist_add_button( $post_id, $added = false ) {

    global $es_settings;

    if ( $es_settings->is_wishlist_enabled ) {
	    $instance = es_get_wishlist_instance();

	    if ( $instance->has( $post_id ) || $added ) {
		    echo "<a href='#' class='js-es-wishlist-button active' data-id='{$post_id}' data-method='remove'><i class='fa fa-heart' aria-hidden='true'></i></a>";
	    } else {
		    echo "<a href='#' class='js-es-wishlist-button' data-id='{$post_id}' data-method='add'><i class='fa fa-heart-o' aria-hidden='true'></i></a>";
	    }
    }
}
add_action( 'es_wishlist_add_button', 'es_wishlist_add_button', 10, 1 );

/**
 * Add item to the wishlist.
 *
 * @return void
 */
function es_ajax_wishlist() {

    if ( check_ajax_referer( 'es_wishlist_nonce', 'nonce' ) ) {
        $property_id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
        $method = filter_input( INPUT_POST, 'method' );

        if ( $property_id ) {
            $property = es_get_property( $property_id );
            $entity = $property->get_entity();

            if ( $entity->post_type == $property::get_post_type_name() ) {
                $wishlist = es_get_wishlist_instance();

                if ( 'remove' == $method ) {
                    $wishlist->remove( $property_id );
                    $response = array( 'status' => 'success', 'data' => es_wishlist_get_button( $property_id ) );

                } else if ( 'add' == $method ) {

                    if ( $wishlist->add( $property_id ) ) {
	                    $response = array( 'status' => 'success', 'data' => es_wishlist_get_button( $property_id, true ) );
                    } else {
	                    $response = array( 'status' => 'error', 'data' => __( 'Item didn\'t added to the wishlist', 'es-plugin' ) );
                    }
                } else {
	                $response = array( 'status' => 'error', 'message' => __( 'Incorrect wishlist action.', 'es-plugin' ) );
                }
            } else {
	            $response = array( 'status' => 'error', 'message' => __( 'Incorrect property type.', 'es-plugin' ) );
            }
        } else {
            $response = array( 'status' => 'error', 'message' => __( 'Incorrect property.', 'es-plugin' ) );
        }
    } else {
	    $response = array( 'status' => 'error', 'message' => __( 'Invalid security nonce. Please, refresh the page.', 'es-plugin' ) );
    }

    wp_die( json_encode( $response ) );
}
add_action( 'wp_ajax_nopriv_es_wishlist_add', 'es_ajax_wishlist' );
add_action( 'wp_ajax_es_wishlist_add', 'es_ajax_wishlist' );

add_action( 'wp_ajax_nopriv_es_wishlist_remove', 'es_ajax_wishlist' );
add_action( 'wp_ajax_es_wishlist_remove', 'es_ajax_wishlist' );



/**
 * Return save search entity instance.
 *
 * @param null $id
 *
 * @return Es_Saved_Search
 */
function es_get_saved_search( $id = null ) {
    return apply_filters( 'es_get_saved_search', new Es_Saved_Search( $id ) );
}

/**
 * Return user entity.
 *
 * @param integer $user_id .
 *
 * @param null $role
 *
 * @return Es_User|false
 */
function es_get_user_entity( $user_id = null, $role = null ) {

	$user = get_user_by( 'ID', $user_id );

	$role = $role ? $role : Es_Buyer::get_role_name();

	if ( ( ! empty( $user->roles ) && in_array( $role, $user->roles ) ) || ( ! $user_id && $role == Es_Buyer::get_role_name() ) ) {
		return apply_filters( 'es_get_user_entity', es_get_buyer( $user_id ), $user_id, $role );
	}

	return apply_filters( 'es_get_user_entity', false, $user_id, $role );
}

/**
 * @param null $user_id
 *
 * @return Es_Buyer
 */
function es_get_buyer( $user_id = null ) {

    return apply_filters( 'es_get_buyer', new Es_Buyer( $user_id ) );
}

/**
 * Return login form via ajax.
 *
 * @return void
 */
function es_ajax_login_form() {

    if ( check_ajax_referer( 'es_front_nonce', 'nonce' ) ) {

	    $login_shortcode = new Es_Login_Shortcode();

        echo "<div class='white-popup es-login-popup'>";

	    echo $login_shortcode->build();

	    echo "</div>";

	    die;
    }
}
add_action( 'wp_ajax_nopriv_es_login_form', 'es_ajax_login_form' );

/**
 * @return array
 */
function es_get_plugin_user_roles() {

    return apply_filters( 'es_get_plugin_user_roles', array( Es_Buyer::get_role_name() ) );
}

/**
 * @param $content
 *
 * @return string
 */
function es_title_format( $content ) {
	return '%s';
}
add_filter('private_title_format', 'es_title_format');

/**
 * @param $template
 * @param array $variables
 *
 * @return string
 */
function es_email_content( $template, $variables = array() ) {

    ob_start();
    es_load_template( $template, 'front', null, $variables );
    $content = ob_get_clean();

    ob_start();

	es_load_template( 'emails/template.php', 'front', null, array(
		'content' => $content,
	) );

    return ob_get_clean();
}

/**
 * Set email content type.
 *
 * @return string
 */
function es_mail_set_content_type() {

	return "text/html";
}
add_filter( 'wp_mail_content_type','es_mail_set_content_type' );

/**
 * Add button above tinymce editor for build estatik shortcodes.
 *
 * @param $editor_id
 *
 * @return void
 */
function add_shortcode_builder_button( $editor_id ) {

	$screen = get_current_screen();
	$page = filter_input( INPUT_GET, 'page' );

    if ( ( ! empty( $screen->id ) && $screen->id != 'properties' ) || ( empty( $screen ) && current_user_can( 'edit_posts' ) ) ) {
        if ( is_admin() && $page != 'es_settings'  ) {
	        wp_enqueue_style( 'es-magnific-style' );
	        wp_enqueue_style( 'es-select2-style' );
	        wp_enqueue_script( 'es-shortcode-builder-script', ES_PLUGIN_URL . 'admin/assets/js/custom/shortcode-builder.js', array( 'jquery', 'es-magnific-script', 'es-select2-script' ) );

	        $args = array(
		        'action' => 'es_shortcode_builder_popup',
		        'nonce' => wp_create_nonce( 'es_shortcode_builder_popup' ),
		        'context' => $editor_id
	        );

	        echo "<style>
	        .es-sb-btn {
              box-shadow: none !important;
              opacity: 0.8;
              display: inline-block;
              width: 38px;
            }

            .es-sb-btn:hover {
              opacity: 1;
              border: 0;
            }

            .es-sb-btn img {
                width: 30px;
                height: auto;
                position: relative;
                top: 2px;
            }
	    </style>";

	        echo "<a title='" . __( 'Estatik Shortcode Builder', 'es-plugin' ) . "' href='" . add_query_arg( $args, admin_url( 'admin-ajax.php' ) ) . "' class='js-es-shortcode-builder__link es-sb-btn' data-editor='{$editor_id}'>
	        <img src='" . ES_ADMIN_IMAGES_URL . 'estatik.svg' . "' alt='" . __( 'Estatik Shortcode Builder', 'es-plugin' ) . "' width='30' height='auto'>
	    </a>";
        }
    }
}
add_action( 'media_buttons', 'add_shortcode_builder_button', 300 );

/**
 * Call estatik shortcode builder via ajax call.
 *
 * @return void
 */
function es_ajax_shortcode_builder_popup() {

    if ( check_ajax_referer( 'es_shortcode_builder_popup', 'nonce' ) && current_user_can( 'edit_posts' ) ) {
	    include ES_ADMIN_TEMPLATES . '/shortcode-builder/popup.php';
	    wp_die();
    }
}
add_action( 'wp_ajax_es_shortcode_builder_popup', 'es_ajax_shortcode_builder_popup' );

/**
 * Load shortcode params via ajax call.
 *
 * @return void
 */
function es_ajax_shortcode_builder_params() {

    $shortcode = filter_input( INPUT_GET, 'shortcode' );

    include( ES_ADMIN_TEMPLATES . 'shortcode-builder/params/' . $shortcode . '-params.php' );

    wp_die();
}
add_action( 'wp_ajax_es_shortcode_builder_params', 'es_ajax_shortcode_builder_params' );

/**
 * Search properties for select2 field.
 *
 * @return void
 */
function es_ajax_select2_search_properties() {

    if ( check_ajax_referer( 'es_admin_nonce', 'nonce' ) ) {
	    $q = filter_input( INPUT_GET, 'q' );
	    $response = array();

	    if ( $q ) {
		    $posts = get_posts( array(
			    'post_type' => 'properties',
			    'fields' => 'ids',
			    'posts_per_page' => 20,
			    's' => $q,
		    ) );

		    $posts_res = array();

		    if ( $posts ) {
			    foreach ( $posts as $post ) {
				    $posts_res[] = array( 'id' => $post, 'text' => get_the_title( $post ) );
			    }

			    $response['results'] = $posts_res;
		    }
	    }

	    wp_die( json_encode( $response ) );
    }
}
add_action( 'wp_ajax_es_select2_search_properties', 'es_ajax_select2_search_properties' );

/**
 * Build shortcode ajax action.
 *
 * @return void
 */
function es_ajax_build_shortcode() {

    if ( check_ajax_referer( 'es_build_shortcode', 'es_build_shortcode_nonce' ) ) {

        $shortcode_name = sanitize_key( filter_input( INPUT_GET, 'shortcode_name' ) );

	    if ( $shortcode_name ) {
		    $attributes_string = '';

		    if ( ! empty( $_GET['attr'] ) && is_array( $_GET['attr'] ) ) {
			    foreach ( $_GET['attr'] as $attr => $value ) {

				    if ( is_string( $value ) && strlen( $value ) ) {
					    $attributes_string .= ' ' . sanitize_text_field( $attr ) . '="' . sanitize_text_field( $value ) . '"';
				    }

				    if ( is_array( $value ) && ! empty( $value ) ) {
					    $value = array_map( 'sanitize_text_field', $value );
					    $attributes_string .= ' ' . sanitize_text_field( $attr ) . '="' . implode( ',', $value ) . '"';
				    }
			    }
		    }

		    wp_die( sprintf( '[%s%s]', $shortcode_name, $attributes_string ) );
	    }
    }
}
add_action( 'wp_ajax_es_build_shortcode', 'es_ajax_build_shortcode' );

/**
 * WP Multilang Support.
 *
 * @param $config
 *
 * @return mixed
 */
function es_wpm_load_config( $config ) {

    $config['post_types']['properties'] = array();

    return $config;
}
add_filter( 'wpm_load_config', 'es_wpm_load_config' );

/**
 * Display Property field value.
 *
 * @param array $atts
 *
 * @return mixed|null
 */
function es_property_field_shortcode( $atts = array() ) {

	$atts = shortcode_atts( array(
		'name' => '',
		'property_id' => get_the_ID(),
	), $atts );

	if ( empty( $atts['name'] ) && empty( $atts['property_id'] ) ) return null;
	$value = '';

    if ( $finfo = Es_Property::get_field_info( $atts['name'] ) ) {
        if ( ! empty( $finfo['formatter'] ) ) {
            $value = es_get_the_formatted_field( $atts['name'], $finfo['formatter'] );
        } else {
            $value = es_get_the_property_field( $atts['name'], $atts['property_id'] );
        }
    }

    return is_array( $value ) ? implode( ', ', $value ) : $value;
}
add_shortcode( 'es_property_field', 'es_property_field_shortcode' );

/**
 * Enqueue shortcode builder scripts for elementor builder.
 *
 * @return void
 */
function es_elementor_sb_enqueue() {

    $adminVendor = 'admin/assets/js/vendor/';
	$custom = 'admin/assets/css/custom/';
	$vendor_main = 'assets/css/vendor/';
	$custom_js = 'admin/assets/js/custom/';

	$deps = array (
		'jquery'
	);

	$custom_main = 'assets/js/custom/';

	$language = es_get_locale();

	global $es_settings;

	if ( $es_settings->google_api_key ) {
		wp_register_script(
			'es-admin-map-script', ES_PLUGIN_URL . $custom_main . 'map.min.js',
			array( 'es-admin-googlemap-api' ),
			false
		);

		wp_register_script(
			'es-admin-googlemap-api',
			'https://maps.googleapis.com/maps/api/js?key=' . $es_settings->google_api_key . '&libraries=places&language='.$language,
			array(),
			false
		);

		$deps[] = 'es-admin-map-script';
	}

	wp_enqueue_script( 'es-admin-script', ES_PLUGIN_URL . $custom_js . 'admin.js', $deps );

	wp_enqueue_style( 'es-admin-style', ES_PLUGIN_URL . $custom . 'admin.css' );
	wp_register_script( 'es-select2-script', ES_PLUGIN_URL . $adminVendor . 'select2.min.js', array ( 'jquery' ) );
	wp_register_script( 'es-magnific-script', ES_PLUGIN_URL . 'assets/js/vendor/' . 'jquery.magnific-popup.min.js', array ( 'jquery' ) );
	wp_enqueue_style( 'es-magnific-style', ES_PLUGIN_URL . $vendor_main . 'magnific-popup.min.css' );
	wp_enqueue_style( 'es-select2-style' );
	wp_enqueue_script( 'es-shortcode-builder-script', ES_PLUGIN_URL . 'admin/assets/js/custom/shortcode-builder.js', array( 'jquery', 'es-select2-script', 'es-magnific-script' ) );
	wp_localize_script( 'es-shortcode-builder-script', 'Estatik', Estatik::register_js_variables() );
}
add_action( 'elementor/editor/before_enqueue_scripts', 'es_elementor_sb_enqueue' );

/**
 * @param $title
 *
 * @return string
 */
function es_sanitize_title_intl( $str ) {

	$chars = array(
		"Є"=>"YE","І"=>"I","Ѓ"=>"G","і"=>"i","№"=>"#","є"=>"ye","ѓ"=>"g",
		"А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
		"Е"=>"E","Ё"=>"YO","Ж"=>"ZH",
		"З"=>"Z","И"=>"I","Й"=>"J","К"=>"K","Л"=>"L",
		"М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
		"С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"X",
		"Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
		"Ы"=>"Y","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA",
		"а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
		"е"=>"e","ё"=>"yo","ж"=>"zh",
		"з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
		"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
		"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"x",
		"ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
		"ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
		"—"=>"-","«"=>"","»"=>"","…"=>""
	);

    return strtr( $str, $chars );
}

add_action( 'sanitize_title', 'es_sanitize_title_intl', 8 );

/**
 * Configure wp autop.
 *
 * @return void
 */
function es_autop() {
    global $es_settings;

    if ( $es_settings->wp_autop && is_singular('properties') ) {
	    remove_filter('the_content', 'wpautop');
	    add_filter( 'the_content', 'es_wpautop', 10, 2 );
    }
}
add_action( 'wp_head', 'es_autop' );

/**
 * Remove br tags from content.
 *
 * @param $pee
 * @param bool $br
 *
 * @return string
 */
function es_wpautop( $pee, $br = false ) {

    if ( is_singular( 'properties' ) ) {
        return $pee;
    }

    return wpautop( $pee, $br );
}

/**
 * Saving properties addresses for estatik plugin.
 *
 * @param $post_id
 * @param $key
 * @param $value
 */
function es_import_post_meta( $post_id, $key, $value ) {

    if ( $key == 'es_property_address_components' && ! empty( $value ) ) {
        $property = es_get_property( $post_id );
        $property->save_address_components( json_decode( $value ) );
    }
}
add_action( 'import_post_meta', 'es_import_post_meta', 10, 3 );

/**
 * Render search widget fields action
 *
 * @param $name
 *    Field name.
 *
 * @param $widget_instance
 *    Search Widget intsance array.
 */
function es_search_render_field( $name, $widget_instance ) {
	echo Es_Search_Widget::render_field( $name, $widget_instance );
}
add_action( 'es_search_widget_render_field', 'es_search_render_field', 10, 2 );

/**
 * @param $a
 * @param $b
 *
 * @return bool
 */
function es_uksort_sections_callback( $a, $b ) {
    $order1 = ! empty( $a['order'] ) ? $a['order'] : 0;
    $order2 = ! empty( $b['order'] ) ? $b['order'] : 0;

	if ( $order1 == $order2 ) return 0;

	return $order1 < $order2 ? -1 : 1;
}

/**
 * @param $var
 *
 * @return bool
 */
function es_filter_sections( $var ) {
    return ! empty( $var['section'] );
}

/**
 * Verify recaptcha request.
 *
 * @return bool
 */
function es_verify_recaptcha() {

	if ( isset( $_POST['g-recaptcha-response'] ) ) {
		if ( ! empty( $_POST['g-recaptcha-response'] ) ) {
			global $es_settings;
			$secret = $es_settings->recaptcha_secret_key;

			$verifyResponse = wp_safe_remote_get('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . sanitize_text_field( $_POST['g-recaptcha-response'] ) );

			if ( ! empty( $verifyResponse['body'] ) ) {
				$responseData = json_decode( $verifyResponse['body'] );

				if ( ! empty( $responseData->success ) || ( ! empty( $responseData->score ) && $responseData->score >= 0.5 ) ) {
					return apply_filters( 'es_verify_recaptcha', true );
				}
			}
		}
		return apply_filters( 'es_verify_recaptcha', false );
	}

	return apply_filters( 'es_verify_recaptcha', true );
}

/**
 * Filter image sizes for demo content.
 *
 * @return array
 */
function es_demo_filter_image_sizes() {
	$sizes = array_keys( Estatik::get_image_sizes() );
	$sizes = array_merge( $sizes, array( 'thumbnail' ) );

	return $sizes;
}

/**
 * Check empty section.
 *
 * @param $var
 *
 * @return bool
 */
function es_check_section( $var ) {
	return ! empty( $var['section'] );
}

/**
 * Display plugin notifications messages.
 *
 * @param $context
 */
function es_render_notifications( $context ) {

    global $es_notifications_context, $es_notifications;

    if ( ! empty( $es_notifications_context ) && $es_notifications_context ==  $context ) {
	    if ( ! empty( $es_notifications ) ) {
		    foreach ( $es_notifications as $message ) {
			    $message['message'] = $message['type'] == 'error' ?
				    '<i class="fa fa-times-circle-o" aria-hidden="true"></i> ' . $message['message'] :
				    '<i class="fa fa-check-circle-o" aria-hidden="true"></i> ' . $message['message'];

			    echo '<p class="es-message es-message-' . $message['type'] . '" >' . $message['message'] . '</p>';
		    }
	    }
    }
}
add_action( 'es_render_notifications', 'es_render_notifications' );

/**
 * @param $notifications
 * @param $context
 */
function es_set_notifications( $notifications, $context ) {
	global $es_notifications_context, $es_notifications;
	$es_notifications_context = $context;
	$es_notifications = $notifications;
}

/**
 * Is ajax checker.
 *
 * @return bool
 */
function es_is_ajax() {
	return function_exists( 'wp_doing_ajax' ) ? wp_doing_ajax() : defined( 'DOING_AJAX' );
}

/**
 * Add fbuilder select box values.
 *
 * @param $value
 *    Field value.
 * @param $field
 *    Field name.
 * @param $entity_id
 *    Entity ID.
 *
 * @return mixed
 */
function es_save_property_dropdown_values( $value, $field, $entity_id ) {
	$entity = es_get_property( $entity_id );
	$field_info = $entity::get_field_info( $field );

	if ( $value && ! empty( $field_info['fbuilder'] ) && ! empty( $field_info['type'] ) && $field_info['type'] == 'list' && ! empty( $field_info['fbuilder_field_id'] ) ) {
		if ( $field = Es_FBuilder_Helper::get_field( $field_info['fbuilder_field_id'] ) ) {
			$values = is_array( $field['values'] ) ? $field['values'] : array();
			$old_count = count( $values );

			$value = ! is_array( $value ) ? array( $value ) : $value;
			$value = array_filter( $value );

			if ( ! empty( $value ) ) {
				foreach ( $value as $single_value ) {
					if ( ! isset( $values[ $single_value ] ) ) {
						$values[ $single_value ] = $single_value;
					}
				}
			}

			if ( $old_count != count( $values ) ) {
				global $wpdb;
				$wpdb->update( $wpdb->prefix . 'fbuilder_fields',
					array( 'values' => serialize( $values ) ),
					array( 'id' => $field_info['fbuilder_field_id'] )
				);
			}
		}
	}

	return $value;
}
add_filter( 'es_save_property_field_value', 'es_save_property_dropdown_values', 10, 3 );

/**
 * @param $post_id
 * @param $post
 * @param $update
 */
function es_save_property_call_for_price( $post_id, $post, $update ) {
    if ( ! $update ) {
	    $property = es_get_property( $post_id );
	    $property->save_field_value( 'call_for_price', 0 );
	    $property->save_field_value( 'featured', 0 );
    }
}
add_action( "save_post_properties", 'es_save_property_call_for_price', 10, 3 );

/**
 * Auto tags function.
 *
 * @param $post_id
 * @param $post
 */
function es_append_property_tags( $post_id, $post ) {
    global $es_settings;
	if ( ! empty( $post->post_content ) && ! empty( $es_settings->is_tags_enabled ) ) {

		$append_tags = array();

		$tags = get_terms( array(
			'taxonomy' => 'es_tags',
			'hide_empty' => false,
			'fields' => 'id=>name',
		) );

		if ( ! empty( $tags ) ) {
			foreach ( $tags as $id => $tag ) {
				if ( stristr( $post->post_content, $tag ) ) {
					$append_tags[] = $id;
				}
			}
		}

		if ( ! empty( $append_tags ) ) {
			wp_set_post_terms( $post_id, $append_tags, 'es_tags', true );
		}
	}
}
add_action( "save_post_properties", 'es_append_property_tags', 10, 2 );

/**
 * @param $string
 *
 * @return mixed|null|string|string[]
 */
function es_strtolower( $string ) {
	return function_exists( 'mb_strtolower' ) ? mb_strtolower( $string ) : strtolower( $string );
}

/**
 * @param $where
 * @param $wp_query
 * @return string
 */
function es_extend_wp_query_title( $where, $wp_query ) {
    if ( $title_search = $wp_query->get( 'title_search' ) ) {
        $where .= " AND (post_title LIKE '%{$title_search}%')";
    }
    return $where;
}

add_filter( 'posts_where', 'es_extend_wp_query_title', 10, 2 );

add_filter( 'es_property_get_fields', 'es_property_alt_desc_field', 11 );

/**
 * @param $fields
 * @return mixed
 */
function es_property_alt_desc_field( $fields ) {

    $id = get_the_ID();

    if ( $id ) {
        $elementor_editor_mode = es_is_elementor_builder_enabled( $id );
        $divi_builder = function_exists( 'et_pb_is_pagebuilder_used' ) && et_pb_is_pagebuilder_used( $id );

        if ( ! $elementor_editor_mode && ! $divi_builder ) {
            unset( $fields['alternative_description'] );
        }
    }

    return $fields;
}

/**
 * @param int $post_id
 * @return bool
 */
function es_is_elementor_builder_enabled( $post_id = 0 ) {
    $post_id = $post_id ? $post_id : get_the_ID();

    return get_post_meta( $post_id, '_elementor_edit_mode', true ) == 'builder'
        && defined( 'ELEMENTOR_VERSION' );
}

/**
 * Sanitize provided value.
 *
 * @param $var
 *
 * @return array|string
 */
function es_clean( $var ) {
    if ( is_array( $var ) ) {
        return array_map( 'es_clean', $var );
    } else {
        if ( is_scalar( $var ) ) {
            if ( es_is_html( $var ) ) {
                return esc_attr( $var );
            }
        } else {
            return sanitize_text_field( $var );
        }

        return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
    }
}

/**
 * @param $string
 *
 * @return bool
 */
function es_is_html( $string ) {
    return $string != strip_tags( $string );
}

add_action( 'es_property_fields_icons', 'es_property_fields_icons' );


add_filter( 'es_settings_get_value', 'es_prepare_property_fields_icons_uri', 10, 2 );

/**
 * @param $value
 * @param $field
 * @return array
 */
function es_prepare_property_fields_icons_uri( $value, $field ) {
    if ( 'property_fields_icons' == $field ) {
        $need_update = false;

        if ( ! empty( $value ) && is_array( $value ) ) {
            foreach ( $value as $key => $item ) {
                if ( empty( $item['icon_url'] ) ) continue;

                if ( stristr( $item['icon_url'], WP_PLUGIN_URL ) ) {
                    if ( ! stristr( $item['icon_url'], ES_PLUGIN_URL ) ) { // !
                        $u = $item['icon_url'];
                        if ( stristr( $u, 'squirefit_icon.png' ) || stristr( $u, 'bed_icon.png' ) || stristr( $item['icon_url'], 'bath_icon.png' ) ) {
                            $filename = basename( $u );
                            $value[ $key ]['icon_url'] = ES_PLUGIN_URL . 'assets/images/' . $filename;
                            $need_update = true;
                        }
                    }
                }
            }
        }

        if ( $need_update ) {
            global $es_settings;
            $es_settings->saveOne( 'property_fields_icons', $value );
        }
    }

    return $value;
}

if ( ! function_exists( 'es_property_fields_icons' ) ) {

    /**
     * Display meta icons in archive property box.
     *
     * @param $post_id
     */
    function es_property_fields_icons() {
        global $es_settings;
        $items = $es_settings->property_fields_icons;
        $items = array_filter( $items );

        if ( ! empty( $items ) ) : ?>
            <div class="es-bottom-icon__list"><?php foreach ( $items as $item ) :
                    if ( empty( $item['field'] ) ) continue;
                    $field = Es_Property::get_field_info( $item['field'] );
                    $value = ! empty( $field['formatter'] ) ?
                        es_get_the_formatted_field( $item['field'], $field['formatter'] ) : es_get_the_property_field( $item['field'] );

		            $value = is_array( $value ) ? implode( ', ', $value ) : $value;

                    if ( ! empty( $value ) && ( is_string( $value ) || is_numeric( $value ) ) ) : ?>
                        <span class="es-bottom-icon">
                            <?php if ( ! empty( $item['icon_url'] ) ) : ?>
                                <span class="es-meta-icon es-meta-icon--<?php echo $item['field']; ?>" style="background-image: url(<?php echo $item['icon_url']; ?>);"></span>
                            <?php endif; ?>
                            <?php echo $value; ?>
                        </span>
                    <?php endif; ?>
                <?php endforeach; ?></div>
        <?php endif;
    }
}

/**
 * @return mixed|void
 */
function es_get_auth_networks_list() {
    return apply_filters( 'es_get_auth_networks_list', array( 'facebook', 'google' ) );
}

/**
 * Return social network auth class instance.
 *
 * @param $network
 * @param array $config
 *
 * @return Es_Authentication
 */
function es_get_auth_instance( $network, $config = array() ) {
    $instance = null;

    switch ( $network ) {
        case 'facebook':
            $instance = new Es_Facebook_Authentication( $config );
            break;

        case 'google':
            $instance = new Es_Google_Authentication( $config );
            break;
    }

    return apply_filters( 'es_get_auth_instance', $instance, $network, $config );
}

/**
 * Return redirect url after success auth.
 *
 * @return mixed|void
 */
function es_get_success_auth_redirect_url() {
    global $es_settings;

    if ( $es_settings->user_profile_page_id && get_post_status( $es_settings->user_profile_page_id ) ) {
        $url = get_permalink( $es_settings->user_profile_page_id );
    } else {
        $url = home_url();
    }

    return apply_filters( 'es_get_success_auth_redirect_url', $url );
}

if ( ! function_exists( 'es_login_add_social_networks_buttons' ) ) {

    /**
     * @param $args
     * @return void
     */
    function es_login_add_social_networks_buttons( $args ) {
        $wrap_rendered = false;
        foreach ( es_get_auth_networks_list() as $network ) :
            if ( ! empty( $args[ 'enable_' . $network ] ) ) :
                $auth = es_get_auth_instance( $network, array(
                    'context' => 'login-buttons'
                ) );

                if ( $auth instanceof Es_Authentication && $auth->is_valid() ) :
                    if ( ! $wrap_rendered ) : $wrap_rendered = true; ?><div class="es-login-buttons-wrap"><?php endif; ?>
                    <a class="es-btn es-btn--<?php echo $network; ?> es-btn--auth " href="<?php echo $auth->create_auth_url(); ?>">
                        <i class="fa fa-<?php echo $network; ?>" aria-hidden="true"></i>
                        <?php printf( __( 'Log in with %s', 'es-plugin' ), __( ucfirst( $network ), 'es-plugin' ) ); ?>
                    </a><br>
                <?php endif; ?>
            <?php endif;
        endforeach;

        if ( $wrap_rendered ) : ?></div><?php endif;
    }
}
add_action( 'es_login_after_submit_button', 'es_login_add_social_networks_buttons', 10, 1 );

/**
 * @param array $atts
 *
 * @return false|string
 */
function es_property_gallery( $atts = array() ) {
    $atts = shortcode_atts( array(
        'property_id' => get_the_ID(),
    ), $atts, 'es_gallery' );

    $query = new WP_Query( array( 'post_type' => 'properties', 'p' => $atts['property_id'] ) );

    ob_start();
    if ( $query->have_posts() ) {
	    wp_enqueue_style( 'es-slick-style' );
	    wp_enqueue_style( 'es-slick-theme-style' );

        while ( $query->have_posts() ) {
            $query->the_post();
	        do_action( 'es_single_gallery' );
        }
        $query->reset_postdata();
    }

    return ob_get_clean();
}
add_shortcode( 'es_gallery', 'es_property_gallery' );
add_shortcode( 'es_property_gallery', 'es_property_gallery' );

/**
 * @return string
 */
function es_et_builder_estatik_get_initial_property_content() {
    return '[et_pb_section admin_label="section"]
			[et_pb_row admin_label="row"]
				[et_pb_column type="4_4"][es_single_property_page][/et_pb_column]
			[/et_pb_row]
		[/et_pb_section]';
}

//function es_et_builder_skip_content_activation( $skip, $_post ) {
//    if ( ! et_pb_is_pagebuilder_used( $_post->ID ) ) {
//        return false;
//    }
//
//    $new_old_content          = $_post->post_content;
//    $post_id = $_post->ID;
//
//    // Save old content.
//    $saved_old_content = get_post_meta( $post_id, '_et_pb_old_content', true );
//    $save_old_content  = update_post_meta( $post_id, '_et_pb_old_content', $new_old_content );
//
//    if ( ! $saved_old_content ) {
//        update_post_meta( $post_id, 'es_property_alternative_description', $saved_old_content );
//    }
//
//    if ( false === $save_old_content && $saved_old_content !== $_post->post_content && '' !== $_post->post_content ) {
//        return false;
//    }
//
//    $text_module = '[et_pb_text admin_label="Text"][es_single][/et_pb_text]';
//
//    if ( has_block( 'divi/layout', $post_id ) ) {
//        $updated_content = et_builder_convert_block_to_shortcode( $_post->post_content );
//    } else {
//        // Re-format content.
//        $updated_content = '[et_pb_section admin_label="section"]
//			[et_pb_row admin_label="row"]
//				[et_pb_column type="4_4"]' . $text_module . '[/et_pb_column]
//			[/et_pb_row]
//		[/et_pb_section]';
//    }
//
//    // Update post_content.
//    $_post->post_content = $updated_content;
//
//    // Update post.
//    $update_post = wp_update_post( $_post );
//
//    if ( 0 < $update_post ) {
//        setup_postdata( $_post );
//    }
//
//    return 0 < $update_post;
//
//    return $skip;
//}
//add_filter( 'et_builder_skip_content_activation', 'es_et_builder_skip_content_activation', 10, 2 );
