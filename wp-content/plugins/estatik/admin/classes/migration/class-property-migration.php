<?php

/**
 * Class Es_Property_Migration
 */
class Es_Property_Migration
{
    public static $meta_table = 'estatik_properties_meta';
    public static $property_table = 'estatik_properties';
    public static $agent_table = 'estatik_agents';
    public static $settings_table = 'estatik_settings';

    /**
     * @return Es_Messenger
     */
    public static function get_logger()
    {
        return new Es_Messenger( 'migration_messages' );
    }

    /**
     * Migrate estatik settings from old version.
     *
     * @return void
     */
    public static function migrate_settings()
    {
        global $wpdb, $es_settings; $table = $wpdb->prefix . self::$settings_table;

	    if ( empty( $es_settings ) ) {
		    $es_settings = new Es_Settings_Container();
	    }

        if ( ! self::table_exists( $table ) ) return;

        $col = $wpdb->get_row( "SELECT * FROM $table", ARRAY_A );

        if ( $col ) {
            foreach ( static::get_settings_relation() as $old => $new ) {
                if ( ! isset( $col[ $old ] ) ) continue;

                if ( is_array( $new ) ) {
                    if ( ! isset( $new['compare'][ $col[ $old ] ] ) ) continue;

                    $val = $new['compare'][ $col[ $old ] ];

                    if ( ! empty( $val ) || ( $val == 0 ) ) {
                        $es_settings->saveOne( $new['key'], $new['compare'][ $col[ $old ] ] );
                    }

                } else if ( is_string( $new ) ) {
                    $es_settings->saveOne( $new, $col[ $old ] );
                }

            }
        }
    }

    /**
     * @return mixed
     */
    public static function get_settings_relation()
    {
        return apply_filters( 'es_migration_get_settings_relation', array(
            'address' => 'show_address',
            'currency_sign_place' => 'currency_position',
            'date_format' => 'date_format',

            'default_currency' => array( 'key' => 'currency', 'compare' => array(
                'USD,$' => 'USD',
                'USD' => 'USD',
                '$' => 'USD',
                'usd' => 'USD',
                'EUR' => 'EUR',
                'eur' => 'EUR',
                'euro' => 'EUR',
                'Euro,€' => 'EUR',
                '€' => 'EUR',
                'GBP,£' => 'GBP',
                'GBP' => 'GBP',
                'gbr' => 'GBP',
                '£' => 'GBP',
                'RUB' => 'RUB',
                'rub' => 'RUB',
                '₽' => 'RUB',
                'RUB,₽' => 'RUB',
            ) ),

            'labels' => 'show_labels',
            'date_added' => 'date_added',

            'listing_layout' => array( 'key' => 'listing_layout', 'compare' => array(
                'list' => 'list',
            ) ),

            'no_of_listing' => 'properties_per_page',
            'powered_by_link' => 'powered_by_link',
            'price' => 'show_price',

            'price_format' => array( 'key' => 'price_format', 'compare' => array(
                '2|.|,' => ',.',
                '2|,|.' => '.,',
                '0|| ' => ' ',
            ) ),

            'single_property_layout' => array( 'key' => 'single_layout', 'compare', array(
                3 => 'left',
            ) ),

            'theme_style' => 'theme_style',

            'title' => array( 'key' => 'title_address', 'compare' => array(
                1 => 'title',
                2 => 'address'
            ) ),
        ) );
    }

    /**
     * Return property meta value using key.
     *
     * @param $id
     * @param $meta_key
     *
     * @return null|string
     */
    public static function get_property_meta( $id, $meta_key, $unserialize = false ) {
        global $wpdb;

        $data = $wpdb->get_var( "SELECT prop_meta_value 
                         FROM " . $wpdb->prefix . self::$meta_table . " 
                         WHERE prop_meta_key = '" . $meta_key . "' AND prop_id = '" . $id . "' LIMIT 1" );

        return $unserialize && $data ? maybe_unserialize($data) : $data;
    }

    /**
     * Get property images array from old estatik tables using property ID.
     *
     * @param $id
     * @return null|string
     */
    public static function get_property_images( $id, $unserialize = true )
    {
        return self::get_property_meta( $id, 'images', $unserialize );
    }

    /**
     * Return property custom data using prop ID.
     *
     * @param $id
     * @return null|string
     */
    public function get_property_custom( $id, $unserialize = true )
    {
        return self::get_property_meta( $id, 'prop_custom_field', $unserialize );
    }

    /**
     * Return property related data.
     *
     * @param $id
     * @param $table
     * @param $field
     *
     * @return null|string
     */
    public static function get_property_related_data( $id, $table, $field, $id_field )
    {
        global $wpdb;
        return $wpdb->get_var( "SELECT $field 
                                FROM $table 
                                WHERE $id_field = '" . $id . "'" );
    }

    /**
     * @param $table
     * @return array
     */
    public static function get_table_columns( $table ) {
        global $wpdb;
        return $wpdb->get_col( "DESC $table" );
    }

    /**
     * Return old property data from property table.
     *
     * @param $id
     * @return array|null|object|void
     */
    public static function get_property( $id )
    {
        global $wpdb;
        return $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}" . self::$property_table . " WHERE prop_id = '" . $id . "'" );
    }

    /**
     * Check if table is exist.
     *
     * @param $table
     * @return bool
     */
    public static function table_exists( $table ) {
        global $wpdb;
        return $wpdb->get_var("SHOW TABLES LIKE '$table'") == $table;
    }

    /**
     * Return old properties IDs.
     *
     * @return array
     */
    public static function get_prop_ids()
    {
        global $wpdb;

        if ( self::table_exists( $wpdb->prefix . self::$property_table ) ) {
            return $wpdb->get_col( "SELECT prop_id FROM {$wpdb->prefix}" . self::$property_table);
        } else {
            return null;
        }
    }

    /**
     * Return agent row.
     *
     * @param $id
     * @return array|bool|null|object|void
     */
    public function get_agent( $id )
    {
        global $wpdb;

        try {
            return $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}" . self::$agent_table . " WHERE agent_id = '" . $id . "'" );
        } catch(Exception $e) {
            return false;
        }
    }

    /**
     * @return null|string
     */
    public static function get_default_dimension()
    {
        global $wpdb;
        $default_dimension = 'sq ft';
        $table = $wpdb->prefix . 'estatik_manager_dimension';

        if ( ! self::table_exists( $table ) ) return $default_dimension;

        $dimension = $wpdb->get_var( "SELECT dimension_title FROM $table WHERE dimension_status = 1 LIMIT 1" );

        return $dimension ? $dimension : $default_dimension;
    }

    /**
     * @return array
     */
    public static function get_prepared_dimension_list()
    {
        return apply_filters( 'es_get_prepared_dimension_list', array(
            'sq ft' => 'sq_ft',
            'Sq Ft' => 'sq_ft',
            'SQ FQ' => 'sq_ft',
            'sqft' => 'sq_ft',
            'SQFT' => 'sq_ft',
            'ft2' => 'sq_ft',
            'ft 2' => 'sq_ft',
            'Ft 2' => 'sq_ft',
            'sqm' => 'sq_m',
            'SQM' => 'sq_m',
            'sq m' => 'sq_m',
            'Sq m' => 'sq_m',
            'SQ m' => 'sq_m',
            'm2' => 'sq_m',
            '2m' => 'sq_m',
            'm 2' => 'sq_m',
            'M2' => 'sq_m',
            'acres' => 'acres',
            'acre' => 'acres',
            'ac' => 'acres',
            'acr' => 'acres',
            'HA' => 'hectares',
            'ha' => 'hectares',
            'hectares' => 'hectares',
            'hectare' => 'hectares',
        ) );
    }

    /**
     * Migrate old property to the new version.
     *
     * @param $id
     * @return bool
     */
    public static function migrate_property( $id )
    {
        global $wpdb, $es_settings;

	    if ( empty( $es_settings ) ) {
		    $es_settings = new Es_Settings_Container();
	    }

        // Get property post object.
        $post = get_post( $id );
        // Get property data.
        $property = self::get_property( $id );
        // Get dimensions list.
        $dimension_list = self::get_prepared_dimension_list();
        // Get default dimension.
        $old_dimension = self::get_default_dimension();
        // Searching for correct dimension.
        $dimension = ! empty( $dimension_list[ $old_dimension ] ) ? $dimension_list[ $old_dimension ] : $es_settings->unit;
        $dimension = apply_filters( 'es_migration_dimension', $dimension, $old_dimension, $dimension_list );

		// Check if property is correct.
        if ( empty( $property ) || empty( $post ) ) {
            self::get_logger()->set_message( __( 'Property #' . $id . ' doesn\'t exist.', 'es-plugin' ), 'error' );
            return false;
        }

        $migrated = get_post_meta( $post->ID, 'already_migrated', true );

        // Build post array for updating.
        if ( $post->post_type == Es_Property::get_post_type_name() && ! $migrated ) {
            $post_arr['ID'] = $id;
            $post_arr['post_type'] = Es_Property::get_post_type_name();
            $post_arr['post_title'] = $post->post_title;
            $post_arr['post_status'] = $post->post_status;
            $post_arr['post_content'] = ! empty( $property->prop_description ) ? $property->prop_description : ' ';
        } else {
            self::get_logger()->set_message( __( 'Property #' . $id . ' already migrated.', 'es-plugin' ), 'warning' );
            return false;
        }

		// Update existing post.
        $post_id = wp_insert_post( apply_filters( 'es_migration_postarr', $post_arr  ) );

        if ( $post_id instanceof WP_Error ) {
            self::get_logger()->set_message( $post_id->get_error_message(), 'error' );
            return false;
        } else if ( ! $post_id ) {
			self::get_logger()->set_message( __( 'Old property #' . $id . ' isn\'t updated', 'es-plugin' ), 'error' );
			return false;
		}

        // Get property object.
        $property_obj = es_get_property( $post_id );

		// Get new property fields names.
        $fields = $property_obj::get_fields();

		// Get old property fields names.
		$columns = self::get_table_columns( $wpdb->prefix . self::$property_table );

        $save_data = array();

        if ( $columns ) {
            foreach ( $columns as $column ) {
                $normalized_column = substr( $column, 5 );

                $normalized_column = $normalized_column == 'lotsize' ? 'lot_size' : $normalized_column;
                $normalized_column = $normalized_column == 'builtin' ? 'year_built' : $normalized_column;

                // Save fields from old property to the new property.
                if ( isset( $fields[ $normalized_column ] ) ) {
                    $save_data[ $normalized_column ] = $property->{$column};

                    if ( ! empty( $fields[ $normalized_column ]['units'] ) ) {
                        $save_data[ $fields[ $normalized_column ]['units'] ] = $dimension;
                    }
                } else if ( isset( $fields[ str_replace( '_', '-', $normalized_column ) ] ) ) {
                    $save_data[ str_replace( '_', '-', $normalized_column ) ] = $property->{$column};
                }
            }
        }

        // Get formatted address via google.
        if ( ! empty( $property->latitude ) && ! empty( $property->longitude ) ) {
            $address_data = ES_Address_Components::get_google_components( array( $property->latitude, $property->longitude ) );
        } else if ( ! empty( $property->address ) ) {
            $address_data = ES_Address_Components::get_google_components( $property->address );
        } else {
            $address_data = null;
        }

        // If google returns correct data with address components.
        if ( ! empty( $address_data->status  ) && $address_data->status == 'OK' ) {
            $res = $address_data->results[0];

            if ( empty( $save_data['address'] ) ) {
                $save_data['address'] = $res->formatted_address;
            }

            if ( empty( $save_data['latitude'] ) && empty( $save_data['longitude'] ) ) {
                $save_data['latitude'] = $res->geometry->location->lat;
                $save_data['longitude'] = $res->geometry->location->lng;
            }

            if ( ! empty( $res->address_components ) ) {
                $save_data['address_components'] = json_encode( $res->address_components );
            }
        }

        // Get images of the old property.
        $images = self::get_property_images( $id );

        if ( $images && is_array( $images ) ) {
            $upload_dir = wp_upload_dir();
            $gallery = array();

            add_filter( 'intermediate_image_sizes', 'es_demo_filter_image_sizes' );

            foreach ( $images as $image ) {
                $file = array();
                $image_path = $upload_dir['baseurl'] . $image;

				// If image exists.
                if ( ! empty( $image ) && file_exists( $upload_dir['basedir'] . $image ) ) {
                    $file['name'] = basename( $image_path );
                    $file['tmp_name'] = download_url( $image_path );

                    if ( ! is_wp_error( $file['tmp_name'] ) ) {
                        $attachment_id = media_handle_sideload( $file, $post_id );

                        if ( ! is_wp_error( $attachment_id ) ) {
                            $gallery[] = $attachment_id;
                        } else {
                            self::get_logger()->set_message(
                                $attachment_id->get_error_message(),
                                'error'
                            );
                        }
                    } else {
                        self::get_logger()->set_message(
                            sprintf( __( 'Property %s image: %s', 'es-plugin' ), $post->post_title, $file['tmp_name']->get_error_message() ),
                            'warning'
                        );
                    }
                }
            }

            // Set new images as new property.
            if ( ! empty( $gallery ) ) {
                $save_data['gallery'] = $gallery;
            }
        }

        $property_obj->save_fields( $save_data );

        // Get old property custom data
        $custom = self::get_property_custom( $id );

		// Save it.
        if ( $custom ) {
            foreach ( $custom as $key => $value ) {
                add_post_meta( $post_id, 'es_custom_data', array( trim( $key, "'" ) => $value ), false );
            }
        }

        $related = array(
            $wpdb->prefix . 'estatik_manager_status' => 'status',
            $wpdb->prefix . 'estatik_manager_types' => 'type',
            $wpdb->prefix . 'estatik_manager_categories' => 'category',
            $wpdb->prefix . 'estatik_manager_rent_period' => 'period',
        );

        foreach ( $related as $table => $value ) {
            $field = $value == 'category' ? 'cat' : $value;
            $prop_field = 'prop_' . $value;

            if ( ! empty( $property->{$prop_field} ) ) {
                $data = self::get_property_related_data( $property->{$prop_field}, $table, $field . '_title', $field . '_id' );

                if ( $data ) {
                    $value = $value == 'period' ? 'rent_period' : $value;
                    wp_set_post_terms( $post_id, $data, 'es_' . $value, true );
                }
            }
        }

        $rel_data_many = array( 'feature', 'appliance' );

        foreach ( $rel_data_many as $value ) {
			if ( ! self::table_exists( $wpdb->prefix . "estatik_properties_{$value}s" ) ) continue;

            $term_col = $wpdb->get_col( "SELECT {$value}_id 
                                         FROM " . $wpdb->prefix . "estatik_properties_{$value}s 
                                         WHERE prop_id='$id'" );

            if ( $term_col ) {
                $tax = $value == 'appliance' ? 'es_amenities' : 'es_' . $value;

                foreach ( $term_col as $term_id ) {
                    $title = self::get_property_related_data(
                        $term_id, $wpdb->prefix . 'estatik_manager_' . $value . 's', $value . '_title', $value . '_id'
                    );

                    if ( ! empty( $title ) && taxonomy_exists( $tax ) ) {
                        wp_set_post_terms( $post_id, $title, $tax, true );
                    }
                }
            }
        }

        self::get_logger()->set_message( sprintf( __( 'Property %s successfully migrated', 'es-plugin' ), $post->post_title ), 'success' );
        update_post_meta( $post->ID, 'already_migrated', 1 );
    }
}
