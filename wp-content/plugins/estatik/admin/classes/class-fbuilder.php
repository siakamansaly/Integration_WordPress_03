<?php

/**
 * Class Es_FBuilder
 */
class Es_FBuilder extends Es_Object {

	/**
	 * @return void
	 */
    public function actions() {
        add_action( 'init', array( $this, 'init_filters' ) );
	    parent::actions();
    }

	/**
	 * @return void
	 */
    public function init_filters() {
	    add_filter( 'es_single_property_tabs', array( $this, 'get_single_property_tabs' ), 10, 1 );
	    add_filter( 'es_property_metabox_tabs', array( $this, 'property_metabox_tabs' ), 10, 1 );
	    add_filter( 'es_property_sections', array( $this, 'property_sections' ), 10, 1 );
    }

	/**
	 * @inheritdoc
	 */
	public function filters() {
		add_filter( 'es_single_tabbed_content_after', array( $this, 'single_tabbed_fields' ), 10, 1 );
		add_filter( 'es_single_fields_data', array( $this, 'single_fields_data' ), 10, 1 );
		add_filter( 'es_save_property_field_value', array( $this, 'save_field_value_format' ), 10, 3 );
		add_filter( 'es_get_entity_field_value', array( $this, 'get_field_value_formatted' ), 10, 3 );
		add_filter( 'es_property_get_fields', array( $this, 'filter_private_fields' ), 10, 1 );
		add_filter( 'es_property_get_fields', array( $this, 'remove_base_fields' ), 10, 1 );
		add_filter( 'es_my_listing_default_atts', array( $this, 'shortcode_listings_default_atts' ), 10, 1 );
	}

	/**
	 * @param $atts
	 *
	 * @return mixed
	 */
	public function shortcode_listings_default_atts( $atts ) {

	    global $wpdb;

	    $fields = $wpdb->get_col( "SELECT machine_name FROM {$wpdb->prefix}fbuilder_fields WHERE entity='property' AND `type` NOT IN('url', 'file')" );
	    if ( $fields ) {
	        foreach ( $fields as $field ) {
	            $atts[ $field ] = null;
            }
        }

	    return $atts;
    }

	/**
	 * @param $fields
	 *
	 * @return mixed
	 */
	public function filter_private_fields( $fields ) {

		if ( is_singular( 'properties' ) && $fields ) {

			foreach ( $fields as $field => $options ) {
				if ( ! empty( $options['visible_permission'] ) && ! current_user_can( $options['visible_permission'] ) ) {
					unset( $fields[ $field ] );
				}
			}
		}

		return $fields;
	}

	/**
	 * @param $fields
	 *
	 * @return mixed
	 */
	public function remove_base_fields( $fields ) {

		global $es_settings;

		$page = sanitize_key( filter_input( INPUT_GET, 'page' ) );

		$is_fbuilder_page = $page == 'es_fbuilder' && current_user_can( 'es_delete_dm_item' );

		if ( ! $is_fbuilder_page && $es_settings->property_removed_fields && is_array( $es_settings->property_removed_fields ) ) {
			foreach ( $es_settings->property_removed_fields as $field ) {
				unset( $fields[ $field ] );
			}
		}

		return $fields;
	}

	/**
	 * @return array
	 */
	public function property_sections() {
        $sections = Es_FBuilder_Helper::get_sections( 'property' );
		return $sections;
	}

	/**
	 * @param $tabs
	 *
	 * @return array
	 */
	public function get_single_property_tabs( $tabs ) {

		global $es_settings;

		$sections = Es_FBuilder_Helper::get_sections( 'property' );
		$tabs = array();

		foreach ( $sections as $id => $section ) {
			if ( empty( $section['show_tab'] ) ) continue;
			$tabs[ $id ] = $section['label'];
		}

		return $es_settings->hide_property_top_bar ? array() : $tabs;
	}

	/**
	 * Render section fields.
	 * @param $section_id
	 */
	public function single_tabbed_fields( $section_id ) {
		$fields = Es_FBuilder_Helper::get_entity_fields( 'property', $section_id );
		if ( $data = Es_Property_Single_Page::get_fields_render_data( $fields ) ) : ?>
			<ul class="es-property-single-fields">
				<?php foreach ( $data as $field ) : ?>
					<?php if ( ! empty( $field[ key( $field ) ]['markup'] ) ) : ?>
						<?php echo $field[ key( $field ) ]['markup']; ?>
					<?php elseif ( is_array( $field ) ) : $label = key( $field ); $val = $field[ key( $field ) ]; ?>
                        <li><strong><?php _e( $label, 'es-plugin' ); ?><span class="es-field-delimiter">:</span> </strong><?php _e( $val, 'es-plugin' ); ?></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		<?php endif;
	}

	/**
	 * Filter field value output.
	 *
	 * @param $value
	 * @param $field
	 * @param $entity
	 * @return false|string
	 */
	public function get_field_value_formatted( $value, $field, $entity ) {
		$p = new Es_Property();
		if ( $p->get_entity_prefix() == $entity ) {
			$field = Es_Property::get_field_info( $field );

			global $es_settings;

			if ( ! empty( $field['type'] ) ) {
				if ( ! empty( $value ) && ( 'datetime-local' == $field['type'] || 'date' == $field['type'] ) ) {
				    if ( empty( $field['range_mode'] ) && ! is_array( $value ) ) {
					    $value = date( 'datetime-local' == $field['type'] ? $es_settings->date_format . ' H:i' : $es_settings->date_format, $value );
                    } else {
				        if ( is_array( $value ) ) {
				            $value = implode(' - ', $value);
                        }
                    }
				}
			}
		}

		return $value;
	}

	/**
	 * Filter saving property value.
	 *
	 * @param $value
	 * @param $field
	 * @return mixed
	 */
	public function save_field_value_format( $value, $field, $id ) {

		if ( ! empty( $value ) ) {
			$field_info = Es_Property::get_field_info( $field );

			if ( ! empty( $field_info['type'] ) && in_array( $field_info['type'], array( 'date', 'datetime-local' ) ) ) {
				global $es_settings;
				$format = $es_settings->date_format;
				$format .= $field_info['type'] == 'datetime-local' ? ' H:i' : '';

				if ( ! empty( $field_info['range_mode'] ) ) {
                    if ( is_array( $value ) && ! empty( $value ) ) {
                        foreach ( $value as $key => $val ) {
                            $timestamp = '';

                            if ( ! empty( $val ) ) {
	                            $timestamp = DateTime::createFromFormat( $format, $val );
	                            if ( ! $timestamp ) {
	                                $timestamp = new DateTime();
	                                $timestamp->setTimestamp( strtotime( $val ) );
                                }

	                            if ( $field_info['type'] !== 'datetime-local' ) {
                                    $timestamp->setTime( 0, 0, 0 );
                                }
	                            $timestamp = $timestamp instanceof DateTime ? $timestamp->getTimestamp() : null;
                            }

	                        update_post_meta( $id, 'es_property_' . $field . '-' . $key, $timestamp );
                        }
                    }
                } else if ( ! is_array( $value ) ) {
				    $val = $value;
					$value = DateTime::createFromFormat( $format, $val );
                    if ( ! $value ) {
                        $value = new DateTime();
                        $value->setTimestamp( strtotime( $val ) );
                    }

                    if ( $field_info['type'] !== 'datetime-local' ) {
                        $value->setTime( 0, 0, 0 );
                    }
					$value = $value instanceof DateTime ? $value->getTimestamp() : null;

					if ( $value ) {
						update_post_meta( $id, 'es_property_' . $field, $value );
                    }
                }
			}
		}

		return $value;
	}

	/**
	 * Filter property fields.
	 *
	 * @param $fields
	 * @return array
	 */
	/**
	 * Filter property fields.
	 *
	 * @param $fields
	 * @return array
	 */
	public function single_fields_data( $fields ) {
		$es_property = es_get_property( get_the_ID() );
		$custom = $es_property ? $es_property->get_custom_data() : null;
		$data = array();

		$fields = Es_FBuilder_Helper::get_entity_fields( 'property', 'es-info' );

		if ( $fields ) {
			foreach ( $fields as $key => $field ) {
			    if ( in_array( $key, array( 'bedrooms', 'bathrooms' ) ) ) {
			        unset( $field['formatter'] );
                }
				if ( ! empty( $field['section'] ) && $field['section'] == 'es-info' ) {
					$data[] = array( __( $field['label'], 'es-plugin' ) => ! empty( $field['formatter'] ) ?
						es_get_the_formatted_field( $key, $field['formatter'] ) : es_get_the_property_field( $key ) );
				}
			}
		}

		// Include custom fields.
		if ( ! empty( $custom ) ) {
			foreach ( $custom as $value ) {
				$data[] = array( __( key( $value ), 'es-plugin' ) => __( reset($value), 'es-plugin' ) );
			}
		}

		return apply_filters( 'es_fbuilder_single_fields_data', $data );
	}

	/**
	 * @param $tabs
	 *
	 * @return array
	 */
	public function property_metabox_tabs( $tabs ) {
		$sections = Es_FBuilder_Helper::get_sections( 'property' );

		if ( $sections ) {
			if ( ! empty( $sections ) ) {
				$tabs = array_merge( $sections, $tabs );
			}
		}

        $elementor_editor_mode = es_is_elementor_builder_enabled( get_the_ID() );

        if ( ! $elementor_editor_mode && ! Es_Property_Metabox::tab_has_content( 'es-description' ) ) {
            unset( $tabs['es-description'] );
        }

		unset( $tabs['es-agent'] );

		return $tabs;
	}
}
