<?php

/**
 * Class ES_Address_Components
 */
class ES_Address_Components {

    /**
     * @return array
     */
    public static function get_types() {
        return apply_filters( 'es_address_components_get_types', array(
            'street_address' => __( 'street_address', 'es-plugin' ),
            'route' => __( 'route', 'es-plugin' ),
            'intersection' => __( 'intersection', 'es-plugin' ),
            'political' => __( 'political', 'es-plugin' ),
            'country' => __( 'country', 'es-plugin' ),
            'administrative_area_level_1' => __( 'administrative_area_level_1', 'es-plugin' ),
            'administrative_area_level_2' => __( 'administrative_area_level_2', 'es-plugin' ),
            'administrative_area_level_3' => __( 'administrative_area_level_3', 'es-plugin' ),
            'administrative_area_level_4' => __( 'administrative_area_level_4', 'es-plugin' ),
            'administrative_area_level_5' => __( 'administrative_area_level_5', 'es-plugin' ),
            'colloquial_area' => __( 'colloquial_area', 'es-plugin' ),
            'locality' => __( 'locality', 'es-plugin' ),
            'sublocality' => __( 'sublocality', 'es-plugin' ),
            'neighborhood' => __( 'neighborhood', 'es-plugin' ),
            'premise' => __( 'premise', 'es-plugin' ),
            'subpremise' => __( 'subpremise', 'es-plugin' ),
            'establishment' => __( 'establishment', 'es-plugin' ),
            'landmark' => __( 'landmark', 'es-plugin' ),
            'postal_town' => __( 'postal_town', 'es-plugin' ),
        ) );
    }

	/**
	 * Check if address component is exists.
	 *
	 * @param $long_name
	 * @param $type
	 * @param null $lang
	 *
	 * @return null|string
	 */
    public static function check_component( $long_name, $type, $lang = null ) {
        global $wpdb;

        $result =  $wpdb->get_var(
            $wpdb->prepare(
                "SELECT id
                    FROM " . $wpdb->prefix . "address_components
                    WHERE `long_name` = %s
                    AND `type` = %s AND `locale` = '%s' LIMIT 1", array( $long_name, $type, $lang ) )
        );

        return apply_filters( 'es_address_check_component', $result, $long_name, $type );
    }

    /**
     * @param $type
     * @return mixed
     */
    public static function get_label_by_type( $type ) {
        $labels = apply_filters( 'es_address_components_types', array(
            'street_number' => __( 'St. №', 'es-plugin' ),
            'political' => __( 'District', 'es-plugin' ),
            'route' => __( 'Street', 'es-plugin' ),
            'city' => __( 'City', 'es-plugin' ),
            'state' => __( 'State', 'es-plugin' ),
            'province' => __( 'Province', 'es-plugin' ),
            'street' => __( 'Street', 'es-plugin' ),
            'neighborhood' => __( 'Neighborhood', 'es-plugin' ),
            'locality' => __( 'City', 'es-plugin' ),
            'administrative_area_level_2' => __( 'Province', 'es-plugin' ),
            'administrative_area_level_1' => __( 'State', 'es-plugin' ),
            'administrative_area_level_3' => __( 'State', 'es-plugin' ),
            'country' => __( 'Country', 'es-plugin' ),
            'postal_code`' => __( 'Postal code', 'es-plugin' ),
        ) );

        return apply_filters( 'es_address_components_type_label', ! empty( $labels[ $type ] ) ? $labels[ $type ] : __( 'Unknown', 'es-plugin' ), $type, $labels );
    }

    /**
     * Search address components by query string.
     *
     * @param $q
     * @return array|null|object
     */
    public static function find_components( $q ) {
        global $wpdb;

        $query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}address_components
                                          WHERE long_name LIKE %s OR short_name LIKE %s", array( $q, $q ) );

        $result = $wpdb->get_results( $query );
        return apply_filters( 'es_address_find_components', $result, $q );
    }

    /**
     * Save / update component into table.
     *
     * @param $data
     * @param null $locale
     * @return int
     */
    public static function save_component( $data, $locale = null ) {
        global $wpdb;

        $data->type = $data->types[0];

        $data = (array) $data;
        unset( $data['types'] );

        $data['locale'] = $locale ? $locale : es_get_locale();

        if ( $id = static::check_component( $data['long_name'], $data['type'], $data['locale'] ) ) {
            $wpdb->update( $wpdb->prefix . 'address_components', $data, array( 'id' => $id ) );
        } else {
            $wpdb->insert( $wpdb->prefix . 'address_components', $data );
        }

        $result = empty( $id ) ? $wpdb->insert_id : $id;

        return apply_filters( 'es_address_save_component_id', $result, $data, $locale );
    }

    /**
     * Return address component using ID.
     *
     * @param $id
     * @return array|null|object
     */
    public static function get_component( $id ) {
        global $wpdb;
        $result = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "address_components WHERE id = '$id'");

        return apply_filters( 'es_address_get_component', $result, $id );
    }

    /**
     * Save property component to the database.
     *
     * @param $property_id
     * @param $component_id
     */
    public static function save_property_component( $property_id, $component_id ) {
	    $component_id = intval( $component_id );

        if ( $component = static::get_component( $component_id ) ) {
        	$component = apply_filters( 'es_before_save_property_component', $component, $property_id, $component_id );
            update_post_meta( $property_id, '_address_component_' . $component->type, $component_id );
        }
    }

    /**
     * Get component from post meta.
     *
     * @param $property_id
     * @param $type
     * @return array|null|object
     */
    public static function get_property_component( $property_id, $type ) {
        $component_meta = get_post_meta( $property_id, '_address_component_' . $type, true );
        $result = empty( $component_meta ) ? null : static::get_component( $component_meta );

        return apply_filters( 'es_address_get_property_component', $result, $property_id, $type );
    }

    /**
     * Get list of components using component type.
     *
     * @param $type
     * @param null $locale
     * @return array|null|object
     */
    public static function get_component_list( $type, $locale = null ) {
        global $wpdb;

        $locale = $locale ? $locale : es_get_locale();

        $result = $wpdb->get_results(
	        $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "address_components WHERE `locale` = '$locale' AND `type` = %s
                             GROUP BY long_name ORDER BY long_name", array( $type ) )
        );

        return apply_filters( 'es_address_get_component_list', $result, $type, $locale );
    }

	/**
	 * Return google data using address string or coordinates.
	 *
	 * @param array|string $data
	 *    If passed array - array( lat => n, lng => m )
	 *    If passed string - it's simple address string.
	 *
	 * @return stdClass|null
	 */
	public static function get_google_components( $data ) {

		global $es_settings;

		// Generate google url using coordinates.
		if ( is_array( $data ) && isset( $data[0] ) && isset( $data[1] ) ) {
			$url = sprintf( 'https://maps.googleapis.com/maps/api/geocode/json?latlng=%s,%s&sensor=false&key=%s', trim( $data[0] ), trim( $data[1] ), $es_settings->google_api_key );

			// Generate google url using address string.
		} else if ( is_string( $data ) ) {
			$url = sprintf( "https://maps.google.com/maps/api/geocode/json?sensor=false&address=%s&key=%s", urlencode( $data ), $es_settings->google_api_key );
		}

		// Get google data.
		if ( ! empty( $url ) ) {
			$res = wp_safe_remote_get( $url );

			return json_decode( ! empty( $res['body'] ) ? $res['body'] : array() );
		}

		return null;
	}

    /**
     * Delete address component by ID.
     *
     * @param $id
     */
    public static function delete_component( $id ) {
        global $wpdb;
        $wpdb->delete( $wpdb->prefix . 'address_components', array( 'id' => intval( $id ) ) );
    }
}
