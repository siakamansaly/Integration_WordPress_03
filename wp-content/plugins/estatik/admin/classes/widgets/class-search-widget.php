<?php

/**
 * Class Es_Search_Widget
 */
class Es_Search_Widget extends Es_Widget
{
    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct( 'es_search_widget' , __( 'Estatik Search', 'es-plugin' ), array(
            'description' => __( 'Estatik Search is advanced search widget developed for Estatik plugins.', 'es-plugin' )
        ) );
        add_action( 'es_widget_es_search_widget_page_access_block', array( $this, 'search_page' ), 11, 1 );
    }

	/**
     * @param $instance
     */
    public function search_page( $instance ) {
        $instance['page_id'] = ! empty( $instance['page_id'] ) ? $instance['page_id'] : null;
        $pages[] = __( 'WP Search Page', 'es-plugin' );
        $post_pages = get_pages();

        if ( $post_pages ) {
            foreach ( $post_pages as $page ) {
                $pages[ $page->ID ] = $page->post_title;
            }
        } ?>

        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'page_id' ) ); ?>">
            <?php _e( 'Search page:', 'es-plugin' ); ?>
        </label>
        <select name="<?php echo esc_attr( $this->get_field_name( 'page_id' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'page_id' ) ); ?>" class="widefat">
            <?php foreach ( $pages as $id => $page ) : ?>
                <option <?php selected( $id, $instance['page_id'] ); ?> value="<?php echo $id; ?>"><?php echo $page; ?></option>
            <?php endforeach; ?>
        </select>
        </p><?php
    }

    /**
     * Return layouts of this widget.
     *
     * @return array
     */
    public static function get_layouts()
    {
        return apply_filters( 'es_get_search_widget_layouts', array( 'horizontal' => __( 'Horizontal', 'es-plugin' ), 'vertical' => __( 'Vertical', 'es-plugin' ) ) );
    }

    /**
     * Return search fields.
     *
     * @return array
     */
    public static function get_widget_fields()
    {
        $taxonomies = Es_Taxonomy::get_taxonomies_list();
        $key = array_search( 'es_labels', $taxonomies );

        if ( $key ) unset( $taxonomies[ $key ] );

        $fields = array(
            'address',
            'price',
            'bedrooms',
            'bathrooms',
            'year_built',
            'floors',
            'area',
            'lot_size',
            'country',
            'state',
            'province',
            'city',
            'street',
            'neighborhood',
            'keywords',
            'ID',
        );

        global $es_settings;

        if ( ! empty( $es_settings->property_removed_fields ) ) {
            foreach ( $es_settings->property_removed_fields as $field ) {
                $key = array_search( $field, $fields );
                if ( $key !== false ) {
                    unset( $fields[ $key ] );
                }
            }
        }

        $fields = array_merge( $fields, $taxonomies );

        return apply_filters( 'es_get_widget_fields', $fields );
    }

    /**
     * Function for register widget.
     *
     * @return void
     */
    public static function register()
    {
        register_widget( 'Es_Search_Widget' );
    }

	/**
	 * Render field for search widget.
	 *
	 * @param $name
	 * @param $widget_instance
	 *
	 * @return string|boolean
	 */
    public static function render_field( $name, $widget_instance = null ) {
        $property = es_get_property( null );

        $units_enabled = isset( $widget_instance['enable_unit_converter'] ) ? $widget_instance['enable_unit_converter'] : false;

        // If input data is empty.
        if ( empty( $name ) || ! $field = $property::get_field_info( $name ) ) return false;

        // Field content string;
        $content = null;
        $default_value = ! empty( $field['default_value'] ) ? $field['default_value'] : null;
        // Current field value.
        $value = isset( $widget_instance[ $name ] ) ? $widget_instance[ $name ] : $default_value;
        $value = isset( $_GET['es_search'][ $name ] ) ? $_GET['es_search'][ $name ] : $value;

        if ( is_array( $value ) ) {
            $value = array_map( 'sanitize_text_field', $value );
        } else {
            $value = sanitize_text_field( $value );
        }

        if ( 'ID' == $name ) {
            unset($field['options']['readonly']);
        }

        // Field options.
        $options = ' ';

        if ( empty( $field['options']['id'] ) ) {
            $field['options']['id'] = 'es-search-' . $name . '-input';
        }

        if ( ! empty( $field['options']['required'] ) ) {
            unset( $field['options']['required'] );
        }

        // Set value as data attribute for ajax fields.
        if ( ! empty( $value ) ) {
            $field['options']['data-value'] = $value;
            $field['options']['value'] = $value;
        } else {
			$field['options']['value'] = '';
		}

	    if ( empty( $field['options']['value'] ) && ! empty( $field['default_value'] ) ) {
		    $field['options']['value'] = $field['default_value'];
	    }

	    if ( ! empty( $field['options'] ) ) {
            if ( ! empty( $field['search_range_mode'] ) ) {
                unset( $field['options']['value'] );
            }

            foreach ( $field['options'] as $key => $option ) {
                if ( ( ! empty( $field['search_range_mode'] ) && $key == 'id' ) || is_array( $option ) || ( $key == 'multiple' && empty( $option ) ) ) continue;
                $options .= $key . '="' . esc_attr( $option ) . '" ';
            }
        }

      // Generate label if empty.
      if ( isset( $field['label'] ) && $field['label'] == FALSE ) {
        $field['label'] = '';
      }
      else {
        $label = ! empty( $field['label'] ) ? $field['label'] : __( Es_Html_Helper::generate_label( $name ), 'es-plugin' );
        $field['label'] = '<div class="es-field__label">
				<label for="' . esc_attr( $field['options']['id'] ) . '">
				' . $label . '
				</label></div>';
      }

        $field['type'] = ! empty( $field['search_range_mode'] ) ? 'text' : $field['type'];

        $class_unit = null;

        if ( ! empty( $field['units'] ) ) {
            $class_unit = 'es-field__wrap--units';
        }

        if ( empty( $field['template'] ) ) {
            $content .= '<div class="es-field__wrap ' . $class_unit . '">';
        }

        $multiple = ! empty( $field['options']['multiple'] ) ? '[]' : '';

        if ( ! empty( $field['values_callback'] ) ) {
            $field['values'] = call_user_func_array( $field['values_callback']['callback'], $field['values_callback']['args'] );
        }

        switch ( $field['type'] ) {
            case 'list':
                $content .= '<select name="es_search[' . $name . ']' . $multiple . '" ' . $options .'>';
                if ( ! empty( $field['values'] ) ) {
                    foreach ( $field['values'] as $value => $label ) {
                        $values = ! empty( $field['options']['value'] ) && is_array( $field['options']['value'] ) ?
                            $field['options']['value'] : array();

                        $selected = is_array( $value ) ?
                            selected( true, in_array( $value, $values ), false ) : null;

                        if ( is_array( $field['options']['value'] ) && ! $selected ) {
                            if ( in_array( $value, $field['options']['value'] ) ) {
                                $selected = selected( true, true, false );
                            }
                        } else {
	                        $selected = selected( $value, $field['options']['value'], false );
                        }

                        $content .= '<option value="' . esc_attr( $value ) . '" ' . $selected . '>' . __( $label, 'es-plugin' ) . '</option>';
                    }
                }

                $content .= '</select>';
                break;

            case 'radio':
            case 'checkbox':
                $content .= '<input type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( "es_search[$name]" ) . '" ' . $options . ' ' . checked( $value, $field['options']['value'], false ) . '/>';
                break;

            case 'autocomplete':
                $content .= '<div class="es-autocomplete-wrap js-autocomplete-wrap">';
                $content .= '<input type="text" name="' . esc_attr( "es_search[$name]" ) . '" ' . $options .' data-action="' . esc_attr( $field['autocomplete_action'] ) . '"/>';
                $content .= '<div class="es-autocomplete-result"></div>';
                $content .= '</div>';
                break;

            default:
                if ( ! empty( $field['search_range_mode'] ) ) {
                    $min = isset( $widget_instance[$name . '_min'] ) ? $widget_instance[$name . '_min'] : null;
                    $min = ! empty( $value['min'] ) ? $value['min'] : $min;

                    $max = isset( $widget_instance[$name . '_max'] ) ? $widget_instance[$name . '_max'] : null;
                    $max = ! empty( $value['max'] ) ? $value['max'] : $max;
                    $content .= '<div class="es-field__range">
                                 <input id="' . esc_attr( $field['options']['id'] ) . '-min" type="' . esc_attr( $field['type'] ) . '" placeholder="' . __( 'min', 'es-plugin' ) . '" name="' . esc_attr( "es_search[$name][min]" ) . '" ' . $options .' value="' . esc_attr( $min ) . '"/>';
                    $content .= '<input id="' . esc_attr( $field['options']['id'] ) . '-max" type="' . esc_attr( $field['type'] ) . '" placeholder="' . __( 'max', 'es-plugin' ) . '" name="' . esc_attr( "es_search[$name][max]" ) . '" ' . $options .' value="' . esc_attr( $max ) . '"/>';
                } else {
                    $content .= '<input type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( "es_search[$name]" ) . '" ' . $options .'/>';
                }
        }

        if ( ! empty( $field['units'] ) ) {
            if ( $units_enabled ) {
	            $content .= self::render_field( $field['units'], $widget_instance );
            } else {
                $finfo = Es_Property::get_field_info( $field['units'] );

                if ( ! empty( $finfo['default_value'] ) ) {
                    $content .= ! empty( $finfo['values'][ $finfo['default_value'] ] ) ?
                        sprintf( '<span class="es-field__unit">%s</span>', $finfo['values'][ $finfo['default_value'] ] ) : '';
                }
            }
        }

        if ( ! empty( $field['search_range_mode'] ) ) {
            $content .= '</div>';
        }

        if ( empty( $field['template'] ) ) {
            $content .= '</div>';
        }

        $content = $field['label'] . $content;

        return apply_filters( 'es_search_render_field', $content, $field, $name, $widget_instance );
    }

    /**
     * Return location items for search widget.
     *
     * @return void
     */
    public static function get_location_items()
    {
        $response = null;

        if ( check_ajax_referer( 'es_front_nonce', 'nonce' ) ) {

            $status = sanitize_key( filter_input( INPUT_POST, 'status' ) );
            $field = sanitize_key( filter_input( INPUT_POST, 'type' ) );
            $val = sanitize_key( filter_input( INPUT_POST, 'val' ) );
            $field = Es_Property::get_field_info( $field );

            if ( ! empty( $field['components_types'] ) ) {
                $response = array();

                if ( $status == 'initialize' ) {
                    foreach ( $field['components_types'] as $type ) {
                        $items = ES_Address_Components::get_component_list( $type );

                        if ( $items ) {
                            $response = array_merge( $response, $items );
                        }
                    }

                } else if ( $status == 'dependency' ) {
                    foreach ( $field['components_types'] as $type ) {
                        $items = Es_Search_Location::get_related_location( $type, $val );

                        if ( $items ) {
                            $response = array_replace( $response, $items );
                        }
                    }
                }
            }

	        $response = apply_filters( 'es_search_get_location_items_response', $response );
        }

        wp_die( json_encode( $response ) );
    }

	/**
	 * @param $query WP_Query
	 * @param $data array
	 *
	 * @return WP_Query
	 */
    public static function build_query( $query, $data ) {

        if ( $data ) {
            $property = es_get_property( null );

            global $es_settings;

            foreach ( $data as $field => $value ) {
                if ( empty( $value ) ) continue;

                $field_info = $property::get_field_info( $field );
                $field_key = $property->get_entity_prefix() . $field;

                if ( ! empty( $field_info['skip_search'] ) ) continue;

                switch ( $field ) {
                    case 'ID':
                        $query->set( 'p', $value );
                        break;
                    case 'country':
                    case 'state':
                    case 'province':
                    case 'city':
                    case 'street':
                    case 'neighborhood':
                        if ( ! empty( $field_info['components_types'] ) ) {
                            $meta_query[ $field ] = array( 'relation' => 'OR' );
                            foreach ( $field_info['components_types'] as $components_type ) {
                                $meta_query[ $field ][] = array(
                                    'key' => '_address_component_' . $components_type,
                                    'value' => $value,
                                    'compare' => '=',
                                );
                            }
                        }
                        break;

                    case 'keywords':
                        $query->set( 's', $value );
                        break;

                    case 'address':
                        // Create array from address string using delimiters.
                        if ( ! empty( $value ) ) {

                            $ids = $property::find_by_address( $value );

                            if ( ! empty( $ids ) ) {
                                $query->set( 'post__in', $ids );
                            } else {
                                $query->set( 'post__in', array( -1 ) );
                            }
                        }
                        break;

                    case 'featured':
                    case 'hot':
                    case 'foreclosure':
                    case 'open_house':
                        $meta_query[] = array(
                            'key' => $property->get_entity_prefix() . $field,
                            'value' => $value,
                            'compare' => '=',
                        );
                        break;

                    case 'post_author':
                        if ( ! empty( $value ) ) $query->set( 'author__in', $value );
                        break;

                    default:
                        if ( $field == 'price' && ( ! empty( $value['min'] ) || ! empty( $value['max'] ) ) ) {
                            $meta_query['call_for_price'] = array( 'compare' => '=', 'key' => $property->get_entity_prefix() . 'call_for_price', 'value' => '0' );
                        }

                        if ( ! empty( $field_info['units'] ) ) {
                            if ( ! empty( $data[ $field_info['units'] ] ) ) {
                                $field_key = $property->get_entity_prefix() . $field . '_' . $data[ $field_info['units'] ];
                            }
                        }

                        if ( ! empty( $field_info['search_range_mode'] ) ) {

                            switch ( $field_info['type'] ) {
	                            case 'date':
	                            case 'datetime':
		                            $type = 'NUMERIC';

		                            if ( ! empty( $value ) ) {
			                            if ( is_array( $value ) ) {
				                            foreach ( $value as $key => $t ) {
				                                if ( $t ) {
					                                $timestamp = DateTime::createFromFormat( $es_settings->date_format, $t );
					                                $timestamp->setTime( 0, 0, 0 );
					                                $value[$key] = $timestamp instanceof DateTime ? $timestamp->getTimestamp() : null;
                                                }
				                            }
			                            } else {
				                            $value = strtotime( $value );
				                            if ( $value ) {
                                                $timestamp = new DateTime();
                                                $timestamp->setTimestamp( $value );
                                                $timestamp->setTime( 0, 0, 0 );
                                                $value = $timestamp instanceof DateTime ? $timestamp->getTimestamp() : null;
                                            }
			                            }
		                            }

		                            break;

	                            default:
		                            $type = 'NUMERIC';
                            }

                            if ( ! empty( $value['min'] ) && empty( $value['max'] ) ) {
                                $meta_query[] = array(
                                    'key' => $field_key,
                                    'value' => $field_info['type'] != 'number' ? $value['min'] : (int) $value['min'],
                                    'compare' => '>=',
                                    'type' => $type,
                                );
                            }

                            if ( ! empty( $value['max'] ) && empty( $value['min'] ) ) {
                                $meta_query[] = array(
                                    'key' => $field_key,
                                    'value' => $field_info['type'] != 'number' ? $value['max'] : (int) $value['max'],
                                    'compare' => '<=',
                                    'type' => $type,
                                );
                            }

                            if ( ! empty( $value['min'] ) && ! empty( $value['max'] ) ) {
                                $meta_query[] = array(
                                    'key' => $field_key,
                                    'value' => array( $value['min'], $value['max'] ),
                                    'compare' => 'BETWEEN',
                                    'type' => $type,
                                );
                            }
                        } else if ( ! empty( $field_info['system_type'] ) && 'taxonomy' == $field_info['system_type'] ) {
                            $tax_query['relation'] = 'AND';
                            $tax_query[] = array(
                                'taxonomy' => $field,
                                'field' => 'id',
                                'terms' => $value,
                                'operator' => ! empty( $field_info['search_operator'] ) ? $field_info['search_operator'] : 'AND'
                            );
                        } else {
	                        if ( ! empty( $field_info['options']['multiple'] ) ) {

		                        if ( ! empty( $value ) ) {

			                        $meta_multiple_query['relation'] = 'OR';

			                        foreach ( $value as $_key => $_value ) {
				                        $meta_multiple_query[] = array(
					                        'key' => $field_key,
					                        'value' => $_value,
					                        'compare' => 'LIKE',
				                        );
			                        }

			                        $meta_query[] = $meta_multiple_query;

			                        unset( $meta_multiple_query );
		                        }
	                        } else {
		                        if ( is_array( $value ) ) {
		                            $value = array_filter( $value );

		                            if ( ! empty( $value ) ) {
			                            $meta_query[] = array(
				                            'key' => $field_key,
				                            'value' => $value,
				                            'compare' => 'IN',
			                            );
                                    }
		                        } else {
			                        $meta_query[] = array(
				                        'key' => $field_key,
				                        'value' => stripslashes( $value ),
                                        'compare' => 'LIKE',
			                        );
		                        }
	                        }
                        }
                }
            }

            // If not only relation key exists in tax array.
            if ( ! empty( $tax_query ) && count( $tax_query ) > 1 ) {
                $query->set( 'tax_query', $tax_query );
            }

            if ( ! empty( $meta_query ) ) {
                $query->set( 'meta_query', $meta_query );
            }
        }

        return apply_filters( 'es_search_query', $query );
    }

    /**
     * Customize standard search query.
     *
     * @param WP_Query $query
     * @return void
     */
    public static function pre_get_posts( $query )
    {
        // If query is search.
        if ( ! empty( $_GET['es_search'] ) && is_array( $_GET['es_search'] ) && $query->is_search && ! is_admin() ) {

            $property = es_get_property( null );
            $query->set( 'post_type', $property::get_post_type_name() );

            $search_args = $_GET['es_search'];

            foreach ( $search_args as $key => $value ) {

                if ( is_array( $search_args[ $key ] ) ) {
	                $search_args[ $key ] = array_map( 'sanitize_text_field', $search_args[ $key ] );
                } else {
                    $search_args[ $key ] = sanitize_text_field( $value );
                }
            }

            static::build_query( $query, $search_args );
        }
    }

    /**
     * Return addresses for autocomplete field.
     *
     * @return void
     */
    public static function address_autocomplete_handler() {
        $response = '';

        if ( check_ajax_referer( 'es_front_nonce', 'nonce' ) ) {

	        if ( ! empty( $_POST['s'] ) ) {

		        global $wpdb;
		        $addresses = array();
		        $query = sanitize_text_field( $_POST['s'] );
		        $num_to_show = apply_filters( 'es_autocomplete_addresses_to_show', 10 );

		        $sql = $wpdb->prepare( "SELECT meta_value
                    FROM $wpdb->postmeta as m INNER JOIN $wpdb->posts as p
                    ON p.ID=m.post_id
                    WHERE meta_key = 'es_property_address'
                    AND post_status='publish'
                    AND LOWER(`meta_value`)
                    LIKE '%s'", array( '%' . es_strtolower( $query ) . '%' ) );

		        $results_addresses = $wpdb->get_col( $sql );

		        $prepare = $wpdb->prepare(
			        "SELECT long_name, type FROM {$wpdb->prefix}address_components
                       WHERE long_name LIKE '%s' OR short_name LIKE %s",
			        array( '%' . $query . '%', '%' . $query . '%' )
		        );

		        $results_addresses_components = $wpdb->get_results( $prepare );

		        if ( $results_addresses ) {
			        foreach ( $results_addresses as $address ) {
				        if ( count( $addresses ) >= $num_to_show ) break;
				        $addresses[] = array( 'text' => $address, 'label' => __( 'Address', 'es-plugin' ), 'type' => 'address' );
			        }
		        }

		        if ( $results_addresses_components ) {
			        foreach ( $results_addresses_components as $addresses_component ) {
				        $type = ES_Address_Components::get_label_by_type( $addresses_component->type );
				        $addresses[] = array( 'text' => $addresses_component->long_name, 'label' => $type, 'type' => $addresses_component->type );
			        }
		        }

		        if ( ! empty( $addresses ) ) {
			        $response .= '<ul class="autocomplete-dropdown-list">';
			        foreach ( $addresses as $address ) {
				        if ( $num_to_show <=0 ) break;
				        $response .= '<li data-content="' . $address['text'] . '"><sub class="es-adddress-type es-address-type-' . $address['type'] . '">' . $address['label'] . '</sub>' . $address['text'] . '</li>';
				        $num_to_show--;
			        }
			        $response .= '</ul>';
		        }
	        }

	        $response = apply_filters( 'address_autocomplete_response', $response );
        }

        wp_die( $response );
    }

    /**
     * @inheritdoc
     */
    protected function get_widget_template_path()
    {
        return es_locate_template( 'widgets/es-search-widget.php', 'admin' );
    }

    /**
     * @return string
     */
    protected function get_widget_form_template_path()
    {
	    return es_locate_template( 'widgets/es-search-widget-form.php', 'admin' );
    }
}

add_action( 'widgets_init', array( 'Es_Search_Widget', 'register' ) );
add_action( 'wp_ajax_nopriv_es_get_location_items', array( 'Es_Search_Widget', 'get_location_items' ) );
add_action( 'wp_ajax_es_get_location_items', array( 'Es_Search_Widget', 'get_location_items' ) );
add_action( 'pre_get_posts', array( 'Es_Search_Widget', 'pre_get_posts' ), 20 );
add_action( 'wp_ajax_es_address_autocomplete', array( 'Es_Search_Widget', 'address_autocomplete_handler' ), 20 );
add_action( 'wp_ajax_nopriv_es_address_autocomplete', array( 'Es_Search_Widget', 'address_autocomplete_handler' ), 20 );
