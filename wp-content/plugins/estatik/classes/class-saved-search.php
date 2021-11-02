<?php

/**
 * Class Es_Saved_Search
 *
 * @property $query array
 * @property $update_method string
 * @property $fields string
 */
class Es_Saved_Search extends Es_Post {

    const UPDATE_METHOD_INSTANT = 'instant';
    const UPDATE_METHOD_DAILY = 'daily';
    const UPDATE_METHOD_NO_UPDATE = 'no_update';

    /**
     * @return mixed
     */
    public static function get_fields() {

        return apply_filters( 'esm_saved_search_get_fields', array(
            'query' => array(),
            'update_method' => array(),
	        'fields' => array(),
        ) );
    }

    /**
     * @inheritdoc
     */
    public function get_entity_prefix() {

        return 'es_saved_search_';
    }

    /**
     * @inheritdoc
     */
    public function get_base_field_name() {

        return static::get_post_type_name();
    }

    /**
     * @inheritdoc
     */
    public static function get_post_type_name() {

        return 'es_saved_search';
    }

    /**
     * Return search URL.
     *
     * @return bool|string
     */
    public function get_url() {

        global $es_settings;

        $page_id = $es_settings->all_listings_page_id;

        if ( $page_id && get_post( $page_id ) ) {
            return add_query_arg( $this->query, get_permalink( $page_id ) );
        }

        return false;
    }

    /**
     * @return string
     */
    public function delete_url() {

        return add_query_arg( array(
            'action' => 'es_delete_search',
            'id' => $this->getID(),
            'security' => wp_create_nonce( 'es_delete_search' ),
        ) );
    }

    /**
     * @param $period
     */
    public function change_update_method( $period ) {

        $old = $this->update_method;
        $this->save_field_value( 'update_method', $period );

        if ( $old != $period ) {
            do_action( 'es_saved_search_period_changed', $this->getID(), $period, $old );
        }
    }

	/**
	 * @inheritdoc
	 */
	public function __get( $name )
	{
		$value = $this->get_field_value( $name );
		return apply_filters( 'es_get_entity_field_value', $value, $name, static::get_entity_prefix() );
	}

	/**
	 * Return field label.
	 *
	 * @param $name
	 *
	 * @return null
	 */
	public function get_field_label( $name ) {

		$property = es_get_property( null );
		$field_info = $property::get_field_info( $name );

		return ! empty( $field_info['label'] ) ? $field_info['label'] : null;
	}

	/**
	 * @param $field
	 *
	 * @return mixed
	 */
	public function get_formatted_field( $field ) {

		$property = es_get_property( null );
		$field_info = $property::get_field_info( $field );
		$result = $this->{$field};
		$formatter = ! empty( $field_info['formatter'] ) ? $field_info['formatter'] : null;

		if ( is_array( $result ) ) {
			$field_value = array();

			foreach ( $result as $value ) {
				if ( ! empty( $field_info['system_type'] ) && $field_info['system_type'] == 'taxonomy' ) {
					$term = get_term( $value, $field );
                    if ( $term instanceof WP_Term ) {
                        $field_value[] = $term->name;
                    }
				} else {
					$field_value[] = es_format_field( $value, $formatter );
				}
			}

			if ( ! empty( $field_info['system_type'] ) && $field_info['system_type'] == 'taxonomy' ) {
				return implode( ', ', $field_value );
			} else {
				return implode( ' - ', $field_value );
			}
		}

		if ( ! empty( $field_info['component'] ) && $field_info['component'] == 'address_component' ) {
			$formatter = 'address_component';
		}

		return es_format_field( $result, $formatter );
	}

	/**
	 * @param $data
	 */
	public function save_fields( $data ) {

		parent::save_fields( $data );

		if ( ! isset( $data['update_method'] ) && empty( $this->update_method ) ) {
		    $default_status = apply_filters( 'es_saved_search_default_update_method', static::UPDATE_METHOD_INSTANT );
			$this->save_field_value( 'update_method', $default_status );
		}
	}

	/**
	 * Return list of updates.
	 *
	 * @return array
	 */
	public static function get_periods() {

		return apply_filters( 'es_saved_search_get_periods', array(
			static::UPDATE_METHOD_INSTANT => __( 'Instant Update', 'es-plugin' ),
			static::UPDATE_METHOD_DAILY => __( 'Daily Update', 'es-plugin' ),
			static::UPDATE_METHOD_NO_UPDATE => __( 'No Update', 'es-plugin' ),
		) );
	}

	/**
	 * @inheritdoc
	 */
	public function get_entity_name() {
		return 'saved_search';
	}

	/**
	 * Return view properties URL.
	 *
	 * @return null
	 */
	public function view_properties_url() {

		global $es_settings;

		if ( $es_settings->search_page_id && get_post_status( $es_settings->search_page_id ) == 'publish' ) {
			return add_query_arg( array( 'es_search' =>  $this->query ), get_permalink( $es_settings->search_page_id ) );
		}

		return null;
	}
}
