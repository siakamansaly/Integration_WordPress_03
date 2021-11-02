<?php

/**
 * Class Es_FBuilderRepository
 */
class Es_FBuilder_Helper
{
	public static $sections;
	public static $order;

	/**
	 * @return mixed
	 */
	public static function get_field_types()
	{
		return apply_filters( 'es_fbuilder_field_types', array(
			'text' => __( 'Text', 'es-plugin' ),
			'number' => __( 'Number', 'es-plugin' ),
			'price' => __( 'Price', 'es-plugin' ),
			'area' => __( 'Area', 'es-plugin' ),
			'file' => __( 'File', 'es-plugin' ),
			'list' => __( 'Select', 'es-plugin' ),
			'textarea' => __( 'Text area', 'es-plugin' ),
			'wp_editor' => __( 'Custom HTML', 'es-plugin' ),
			'date' => __( 'Date', 'es-plugin' ),
			'datetime-local' => __( 'Date time', 'es-plugin' ),
			'email' => __( 'Email', 'es-plugin' ),
			'tel' => __( 'Tel', 'es-plugin' ),
			'url' => __( 'Url', 'es-plugin' ),
		) );
	}

	/**
	 * Return field edit link.
	 *
	 * @param $id
	 *    Field ID.
	 * @return string
	 *    Return field edit link.
	 */
	public static function get_field_edit_link( $id ) {
		return add_query_arg( array(
			'action' => 'es-fbuilder-edit-field',
			'id' => $id,
		) );
	}

	/**
	 * Return field edit link.
	 *
	 * @param $id
	 *    Field ID.
	 * @return string
	 *    Return field edit link.
	 */
	public static function get_section_edit_link( $id ) {
		return add_query_arg( array(
				'action' => 'es-fbuilder-edit-section',
				'id' => $id,
			) ) . '#es-es-section-tab';
	}

	/**
	 * Restore standard field link.
	 *
	 * @param $field_id
	 *
	 * @return string
	 */
	public static function get_field_restore_link( $field_id ) {
		return add_query_arg( array(
			'action' => 'es-fbuilder-restore-field',
			'id' => $field_id,
			'nonce' => wp_create_nonce( 'es-fbuilder-restore-field' )
		) );
	}

	/**
	 * Return remove field link.
	 *
	 * @param $id
	 * @return string
	 */
	public static function get_field_delete_link( $id, $is_base_field = false )
	{
		if ( $is_base_field ) {
			return add_query_arg( array(
				'action' => 'es-fbuilder-remove-field',
				'id' => $id,
				'base_field' => true,
				'nonce' => wp_create_nonce( 'es-fbuilder-remove-field' )
			) );
		}

		return add_query_arg( array(
			'action' => 'es-fbuilder-remove-field',
			'id' => $id,
			'nonce' => wp_create_nonce( 'es-fbuilder-remove-field' )
		) );
	}

	/**
	 * Return remove field link.
	 *
	 * @param $id
	 * @return string
	 */
	public static function get_section_delete_link( $id )
	{
		return add_query_arg( array(
			'action' => 'es-fbuilder-remove-section',
			'id' => $id,
			'nonce' => wp_create_nonce( 'es-fbuilder-remove-section' )
		) );
	}

	/**
	 * Check if edit page is active.
	 *
	 * @return bool
	 */
	public static function is_edit_action() {
		return self::get_edit_field() ? true : false;
	}

	/**
	 * @return array|null|object
	 */
	public static function get_edit_field()
	{
		if ( ! empty( $_GET['id'] ) && ! empty( $_GET['action'] ) && $_GET['action'] == 'es-fbuilder-edit-field' ) {

			if ( $field = self::get_field( intval( $_GET['id'] ) ) ) {

				$field['type'] = ! empty( $field['formatter'] ) ? $field['formatter'] : $field['type'];

				return $field;
			}
		}

		return null;
	}

	/**
	 * @return array|null|object
	 */
	public static function get_edit_section()
	{
		$action = sanitize_key( filter_input( INPUT_GET, 'action' ) );

		if ( ! empty( $_GET['id'] ) && $action == 'es-fbuilder-edit-section' ) {

			if ( $field =  self::get_section( intval( $_GET['id'] ) ) ) {
				return $field;
			}
		}

		return null;
	}

	/**
	 * @param $entity string
	 * @param $section string
	 *    Section machine name.
	 *
	 * @param bool $enable_order
	 *
	 * @return mixed
	 */
	public static function get_entity_fields( $entity, $section = null, $enable_order = true ) {
		$result = array();
		$fields = array();

		$callback = apply_filters( 'es_fbuilder_get_entity_section_fields_callback', 'es_get_' . $entity, $entity );

		if ( function_exists( $callback ) ) {
			/** @var Es_Entity $entity */
			$entity = $callback( null );
			$fields = $entity::get_fields();
		}

		if ( $section ) {
			global $wpdb;

			$sql = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fbuilder_fields_order WHERE section_machine_name='%s' ORDER BY id ASC", $section );

			$order = $wpdb->get_results( $sql );

			if ( $order && $enable_order ) {

				foreach ( $fields as $field_key => $field_options ) {
					if ( empty( $field_options['section'] ) || $field_options['section'] != $section ) continue;

					$result[ $field_key ] = $field_options;

					foreach ( $order as $order_item ) {
						if ( empty( $order_item->field_machine_name ) ) continue;

						if ( $order_item->field_machine_name == $field_key ) {
							$order_index = $order_item->order ? $order_item->order : 0;
							$result[ $field_key ]['order'] = $order_index;
						}
					}
				}
				uasort( $result, 'es_uksort_sections_callback' );
			} else {
				$result = $fields;
			}

			foreach ( $result as $key => $field ) {
				if ( empty( $field['section'] ) || ( ! empty( $field['section'] ) && $field['section'] != $section ) ) {
					unset( $result[ $key ] );
				}
			}
		}

		$result = empty( $result ) && empty( $section ) ? $fields : $result;

		return apply_filters( 'es_fbuilder_get_entity_section_fields', $result, $entity );
	}


	/**
	 * @param $entity
	 * @return array|null|object
	 */
	public static function get_fields( $entity ) {
		global $wpdb, $es_settings;

		$sql = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "fbuilder_fields WHERE entity='%s'", $entity );

		$fields = $wpdb->get_results( $sql );
		$result = array();

		if ( $fields ) {
			foreach ( $fields as $key => $field ) {
				$field = (array) $field;
				$field['fbuilder'] = true;
				$field['label'] = ! empty( $field['label'] ) ? __( stripslashes( $field['label'] ), 'es-plugin' ) : null;
				$field['options'] = ! empty( $field['options'] ) ? unserialize( $field['options'] ) : array();
				$field['values'] = ! empty( $field['values'] ) ? unserialize( $field['values'] ) : array();

				if ( $field['type'] === 'wp_editor' ) {
				    $field['validate_callback'] = -1;
                }

				if ( ! empty( $field['values'] ) && is_array( $field['values'] ) ) {
					foreach ( $field['values'] as $val => $label ) {
						$field['values'][ $val ] = __( $label, 'es-plugin' );
					}
				}

				if ( $field['formatter'] == 'area' ) {
					$field['options']['step'] = '0.1';
					$field['units'] = $field['machine_name'] .'_unit';
					$result[ $field['machine_name'] .'_unit' ] = array(
						'type' => 'list',
						'values' => $es_settings::get_setting_values( 'unit' ),
						'template' => true,
						'label' => false,
						'skip_search' => true,
						'default_value' => $es_settings->unit,
					);
				}

				$result[ $field['machine_name'] ] = $field;
			}
		}

		return apply_filters( 'es_fbuilder_get_entity_fields', $result, $entity );
	}

	/**
	 * @param $type
	 *    Field type string.
	 * @return string
	 *    Return template name for input type.
	 */
	public static function get_field_options_template( $type ) {
		$templates = apply_filters( 'es_fbuilder_field_types_templates', array(
			'text' => 'default',
			'number' => 'number',
			'price' => 'number',
			'area' => 'area',
			'file' => 'file',
			'select' => 'multiple',
			'list' => 'multiple',
			'textarea' => null,
			'date' => 'range',
//            'datetime' => 'range',
			'email' => 'default',
			'tel' => 'default',
			'url' => 'default',
		) );

		return ! empty( $templates[ $type ] ) ? $templates[ $type ] : null;
	}

	/**
	 * Return settings field value.
	 *
	 * @param $instance
	 * @param $key
	 * @param null $default
	 * @return null
	 */
	public static function get_settings_value( $instance, $key, $default = null )
	{
		return apply_filters( 'es_fbuilder_get_field_value', ! empty( $instance[ $key ] ) ? $instance[ $key ] : $default, $key, $instance );
	}

	/**
	 * @param $instance
	 * @param $key
	 * @param null $default
	 * @return mixed
	 */
	public static function get_options_value( $instance, $key, $default = null )
	{
		return apply_filters( 'es_fbuilder_get_field_option', ! empty( $instance['options'][ $key ] ) ? $instance['options'][ $key ] : $default, $key, $instance );
	}

	/**
	 * Return sections by entity param.
	 *
	 * @param $entity
	 * @param bool $enable_order
	 * @param bool $overwrite_vars
	 *
	 * @return array
	 */
	public static function get_sections( $entity, $enable_order = true, $overwrite_vars = false )
	{
		global $wpdb;

		$result = array();

		if ( ! static::$sections || $overwrite_vars ) {
			static::$sections = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fbuilder_sections", ARRAY_A );
		}
		$fbuilder_sections = static::$sections;

		if ( $fbuilder_sections ) {
			foreach ( $fbuilder_sections as $key => $value ) {
				$value['fbuilder'] = true;
				if ( ! empty( $value['label'] ) ) {
				    $value['label'] = __( $value['label'], 'es-plugin' );
                }
				$fbuilder_sections[ $value['machine_name'] ] = $value;
				unset( $fbuilder_sections[ $key ] );
			}
		}

		$base_sections = array(
            'es-description' => array(
                'machine_name' => 'es-description',
                'label' => __( 'Description', 'es-plugin' ),
                'render_action' => 'es_single_description_tab',
                'show_tab' => true,
            ),
			'es-info' => array(
				'machine_name' => 'es-info',
				'label' => __( 'Basic facts', 'es-plugin' ),
				'render_action' => 'es_single_info_tab',
				'sortable' => false,
				'show_tab' => true,
			),
			'es-map' => array(
				'machine_name' => 'es-map',
				'label' => __( 'Neighborhood', 'es-plugin' ),
				'render_action' => 'es_single_map_tab',
				'show_tab' => true,
			),
			'es-features' => array(
				'machine_name' => 'es-features',
				'label' => __( 'Features', 'es-plugin' ),
				'render_action' => 'es_single_features',
				'show_tab' => true,
				'icon' => 'es-featured',
			),

			'es-video' => array(
				'machine_name' => 'es-video',
				'label' => __( 'Video', 'es-plugin' ),
				'render_action' => 'es_single_video_tab',
				'show_tab' => true,
			) );

		$sections = array_merge( $base_sections, $fbuilder_sections );

		if ( $enable_order && ( $overwrite_vars || ! static::$order ) ) {
			static::$order = $wpdb->get_results( "SELECT section_machine_name, `order` FROM {$wpdb->prefix}fbuilder_sections_order ORDER BY id ASC" );
		}

		foreach ( $sections as $key => $section ) {
			if ( ! empty( $section['visible_permission'] ) && ! current_user_can( $section['visible_permission'] ) ) {
				unset( $sections[ $key ] );
				continue;
			}

			if ( $enable_order && static::$order ) {
				foreach ( static::$order as $order_item ) {
					if ( ! empty( $order_item->section_machine_name ) ) {
						$order_index = $order_item->order ? $order_item->order : 0;

						if ( $order_item->section_machine_name == $key ) {
							$sections[ $key ]['order'] = $order_index;
						}
					}
				}
			}
		}

		uasort( $sections, 'es_uksort_sections_callback' );

		$result = $sections;

		return apply_filters( 'es_fbuilder_entity_sections', $result, $entity );
	}

	/**
	 * Return sections for select box field.
	 *
	 * @param $entity
	 * @return array
	 */
	public static function get_sections_options( $entity )
	{
		$result = array();

		if ( $data = self::get_sections( $entity ) ) {
			foreach ( $data as $item ) {
				$result[ $item['machine_name'] ] = $item['label'];
			}
		}

		return $result;
	}

	/**
	 * @return null|string
	 */
	public static function get_section_next_order_num() {
		global $wpdb;

		$order = $wpdb->get_var( "SELECT MAX(`order`) FROM {$wpdb->prefix}fbuilder_sections_order" );
		$sections = static::get_sections( 'property', false );

		return $order ? ++$order : ( count( $sections ) + 1 );
	}

	/**
	 * @param $data
	 * @return false|int
	 */
	public static function save_section( $data )
	{
		global $wpdb;

		$data = apply_filters( 'es_fbuilder_before_save_section_data', $data );

		if ( ! empty( $data['id'] ) ) {
			return $wpdb->update( $wpdb->prefix . 'fbuilder_sections', $data, array( 'id' => intval( $data['id'] ) ) );
		} else {
			$machine_name = sanitize_title( self::get_settings_value( $data, 'label' ) . time() . uniqid( 'f' ) );

			$inserted = $wpdb->insert( $wpdb->prefix . 'fbuilder_sections', array_merge( array(
				'machine_name' => $machine_name ), $data )
			);

			if ( $inserted ) {

				$wpdb->insert( $wpdb->prefix . 'fbuilder_sections_order', array(
					'section_machine_name' => $machine_name,
					'order' => static::get_section_next_order_num(),
				) );
			}

			return $inserted;
		}
	}

	/**
	 * Insert or update new field.
	 *
	 * @param $data
	 * @return false|int
	 */
	public static function save_field( $data ) {
		global $wpdb;

		$entity = self::get_settings_value( $data, 'entity', 'property' );

		$values = ! empty( $data['values'] ) ? array_filter ( $data['values'] ) : null;

//		$values = array_map( 'sanitize_text_field', $values );

		$type = self::get_settings_value( $data, 'type' );
		$formatter = $type == 'price' ? 'price' : null;
		$formatter = $type == 'area' ? 'area' : $formatter;
		$formatter = $type == 'url' ? 'url' : $formatter;
		$formatter = $type == 'file' ? 'file' : $formatter;

		$data['tab'] = $data['section'];

		$instance = apply_filters( 'es_fbuilder_field_presave_instance', array(
			'label' => self::get_settings_value( $data, 'label' ),
			'type' => $type == 'price' || $type == 'area' ? 'number' : $type,
			'formatter' => $formatter,
			'tab' => self::get_settings_value( $data, 'tab' ),
			'section' => self::get_settings_value( $data, 'section' ),
			'options' => ! empty( $data['options'] ) ? serialize( $data['options'] ) : null,
			'entity' => $entity,
			'values' => $values ? serialize( array_combine( $values, $values  ) ) : null,
			'rets_support' => self::get_settings_value( $data, 'rets_support' ),
			'search_range_mode' => self::get_settings_value( $data, 'search_range_mode' ),
			'range_mode' => self::get_settings_value( $data, 'range_mode' ),
			'show_thumbnail' => self::get_settings_value( $data, 'show_thumbnail' ),
			'import_support' => self::get_settings_value( $data, 'import_support', 0 ),
			'visible_permission' => self::get_settings_value( $data, 'visible_permission', '' ),
		) );

		if ( ! empty( $data['id'] ) ) {
			$field = self::get_field( $data['id'] );
			if ( $field ) {
				$wpdb->update( $wpdb->prefix . 'fbuilder_fields_order', array( 'section_machine_name' => $instance['section'] ), array( 'field_machine_name' => $field['machine_name'] ) );
				return $wpdb->update( $wpdb->prefix . 'fbuilder_fields', $instance, array( 'id' => $data['id'] ) );
			} else {
				return false;
			}

		} else {
			$machine_name = sanitize_title( self::get_settings_value( $data, 'label' ) . time() . uniqid( 'f' ) );

			$order = $wpdb->get_var( "SELECT MAX(`order`) FROM {$wpdb->prefix}fbuilder_fields_order" );
			$order = ! $order ? count( static::get_entity_fields( 'property', null, false ) ) : $order;

//			if ( ! $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}fbuilder_fields_order" ) ) {
//				$fields = Es_FBuilder_Helper::get_entity_fields( 'property', null, true );
//
//				if ( $fields ) {
//					$fields = array_filter( $fields, 'es_filter_sections' );
//
//					$i = 1;
//					foreach ( $fields as $id => $field ) {
//						if ( ! empty( $field ) ) {
//							$sql = $wpdb->prepare( "SELECT id FROM " . $wpdb->prefix . "fbuilder_fields_order WHERE field_machine_name = '%s'", $id );
//							$check_field = $wpdb->get_var( $sql );
//
//							if ( ! $check_field ) {
//								$wpdb->insert( $wpdb->prefix . 'fbuilder_fields_order', array(
//									'section_machine_name' => 'es-info',
//									'field_machine_name' => $id,
//									'order' => $i,
//								) );
//
//								$i++;
//							}
//						}
//					}
//				}
//			}

			$wpdb->insert( $wpdb->prefix . 'fbuilder_fields_order', array(
				'section_machine_name' => $instance['section'],
				'field_machine_name' => $machine_name,
				'order' => ++$order
			) );

			return $wpdb->insert( $wpdb->prefix . 'fbuilder_fields', array_merge( array(
				'machine_name' => $machine_name ), $instance )
			);
		}
	}

	/**
	 * @param $id
	 * @return array|null|object
	 */
	public static function get_field( $id ) {
		global $wpdb;
		$sql = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "fbuilder_fields WHERE id = %d", intval( $id ) );
		$instance = $wpdb->get_row( $sql, ARRAY_A );

		if ( $instance ) {
			$instance['options'] = ! empty( $instance['options'] ) ? unserialize( $instance['options'] ) : array();
			$instance['values'] = ! empty( $instance['values'] ) ? unserialize( $instance['values'] ) : array();
		}

		return $instance;
	}

	/**
	 * Return section by id.
	 *
	 * @param $id
	 * @return array|null|object
	 */
	public static function get_section( $id ) {
		global $wpdb;

		$sql = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fbuilder_sections WHERE id = %d", $id );

		return $wpdb->get_row( $sql, ARRAY_A );
	}

	/**
	 * Remove field by ID.
	 *
	 * @param $id
	 * @return false|int
	 */
	public static function remove_field( $id )
	{
		global $wpdb;

		$field = self::get_field( $id );
		if ( $field ) {
			$wpdb->delete( $wpdb->prefix . 'fbuilder_fields_order', array( 'field_machine_name' => $field['machine_name'] ) );
			return $wpdb->delete( $wpdb->prefix . 'fbuilder_fields', array( 'id' => intval( $id ) ) );
		}

		return false;
	}

	/**
	 * Remove field by ID.
	 *
	 * @param $id
	 * @return false|int
	 */
	public static function remove_section( $id )
	{
		global $wpdb;

		$field = self::get_section( $id );
		if ( $field ) {
			$wpdb->delete( $wpdb->prefix . 'fbuilder_sections_order', array( 'section_machine_name' => sanitize_key( $field['machine_name'] ) ) );
			return $wpdb->delete( $wpdb->prefix . 'fbuilder_sections', array( 'id' => intval( $id ) ) );
		}

		return false;
	}

	/**
	 * Set sections order.
	 *
	 * @param $sections array
	 *    Sections machine names ordered array.
	 *
	 * @return bool
	 */
	public static function set_sections_order( $sections ) {
		global $wpdb;

		$inserted = true;

		foreach ( $sections as $index => $machine_name ) {

			$machine_name = sanitize_key( $machine_name );

			$wpdb->delete( $wpdb->prefix . 'fbuilder_sections_order', array( 'section_machine_name' => $machine_name ) );

			$inserted = $wpdb->insert( $wpdb->prefix . 'fbuilder_sections_order', array(
				'section_machine_name' => $machine_name,
				'order' => intval( $index ),
			) );

			if ( ! $inserted ) {
				break;
			}
		}

		return $inserted;
	}

	/**
	 * Set fields order by section.
	 *
	 * @param $section string
	 *    Section machine name.
	 * @param $fields array
	 *    Fields machine names ordered array.
	 *
	 * @return bool
	 */
	public static function set_section_fields_order( $section, $fields ) {
		global $wpdb;
		$inserted = true;

		foreach ( $fields as $index => $field_machine_name ) {
			$field_machine_name = sanitize_key( $field_machine_name );
			$section = sanitize_key( $section );
			$wpdb->delete( $wpdb->prefix . 'fbuilder_fields_order', array( 'field_machine_name' => $field_machine_name ) );

			$inserted = $wpdb->insert( $wpdb->prefix . 'fbuilder_fields_order', array(
				'field_machine_name' => $field_machine_name,
				'section_machine_name' => $section,
				'order' => intval( $index ),
			) );

			if ( ! $inserted ) {
				break;
			} else {
				$wpdb->update( $wpdb->prefix . 'fbuilder_fields', array( 'section' => $section, 'tab' => $section ), array( 'machine_name' => $field_machine_name ) );
			}
		}

		return $inserted;
	}
}
