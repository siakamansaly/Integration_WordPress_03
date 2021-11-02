<?php

/**
 * Class Es_Search_Location
 */
class Es_Search_Location
{
    /**
     * Google maps API location types.
     */
    const LOCATION_COUNTRY_TYPE       = 'country';
    const LOCATION_CITY_TYPE          = 'locality';
    const LOCATION_STATE_TYPE         = 'administrative_area_level_1';
    const LOCATION_STREET_TYPE        = 'route';
    const LOCATION_NEIGHBORHOOD_TYPE  = 'neighborhood';
    const LOCATION_PROVINCE_TYPE  = 'administrative_area_level_2';

    /**
     * Return address components list using type and related address component.
     *
     * @param $type
     * @param null $related_component_id
     * @return array
     */
    public static function get_related_location( $type, $related_component_id = null )
    {
        // If related address component is empty.
	    if ( ! $related_component_id || ! ( $component = ES_Address_Components::get_component( $related_component_id ) ) )
		    return ES_Address_Components::get_component_list( $type );

	    global $wpdb;

	    $prop_ids = $wpdb->get_col( "SELECT post_id FROM " . $wpdb->postmeta . "
                                     WHERE meta_key = '_address_component_" . $component->type . "'
                                     AND meta_value = '$related_component_id'" );

	    $result =  ! empty( $prop_ids ) ?  static::get_components_by_posts( $prop_ids, $type ) : null;

	    return apply_filters( 'es_get_related_location', $result, $type, $related_component_id );
    }

    /**
     * Return address components using post id list and type.
     *
     * @param $post_ids
     * @param $type
     * @return array|null|object
     */
    public static function get_components_by_posts( $post_ids, $type )
    {
        global $wpdb;

	    $result = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}address_components
                                        INNER JOIN {$wpdb->postmeta}
                                        ON meta_value = {$wpdb->prefix}address_components.id
                                        WHERE post_id IN(" . implode( ',', $post_ids ) . ")
                                        AND `type` = '$type'
                                        AND meta_key = '_address_component_" . $type . "' GROUP BY long_name" );

        return apply_filters( 'es_get_components_by_posts', $result, $post_ids, $type );
    }
}
