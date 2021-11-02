<?php

/**
 * Class Es_Archive_Sorting
 */
class Es_Archive_Sorting extends Es_Object
{
    /**
     * Sorting actions.
     *
     * @return void
     */
    public function actions()
    {
        add_action( 'es_archive_sorting_dropdown', array( $this, 'sorting_dropdown' ), 10, 2 );
        add_action( 'pre_get_posts',               array( $this, 'pre_get_posts' ), 1 );
        add_action( 'init',                        array( $this, 'redirect_view_sort' ), 1 );
    }

	/**
	 * @return void
	 */
    public function redirect_view_sort() {

    	$redirect_view_sort = sanitize_key( filter_input( INPUT_GET, 'redirect_view_sort' ) );
    	$shortcode_identifier = sanitize_key( filter_input( INPUT_GET, 'shortcode_identifier' ) );

    	if ( ! empty( $redirect_view_sort ) ) {

		    $shortcode_identifier = $shortcode_identifier ? '-' . $shortcode_identifier : '';

    		$args = array(
			    'view_sort' . $shortcode_identifier => $redirect_view_sort,
		    );

    		wp_safe_redirect( add_query_arg( $args, $_SERVER['HTTP_REFERER'] ) ); die;
	    }
    }

    /**
     * Render sorting dropdown.
     *
     * @param string $shortcode_identifier
     *
     * @param null $sort
     * @return void
     */
    public function sorting_dropdown( $shortcode_identifier = '', $sort = null ) {
        $template = self::get_template_path( 'dropdown' );
        if ( file_exists( $template ) ) {
            $parsed_url = wp_parse_args( wp_parse_url( es_get_current_url() ) );
            include( $template );
        }
    }

    /**
     * Handler for filtering properties.
     *
     * @param WP_Query $query
     */
    public function pre_get_posts( $query ) {
    	$taxonomies = Es_Taxonomy::get_taxonomies_list();
        if ( $query->is_post_type_archive( Es_Property::get_post_type_name() ) || $query->is_tax( $taxonomies ) ) {

        	$view_sort = sanitize_key( filter_input( INPUT_GET, 'view_sort' ) );

            if ( $view_sort ) {

                switch ( $view_sort ) {
                    case 'recent':
                        $query->set( 'orderby', 'post_date' );
                        $query->set( 'order', 'DESC' );
                        break;

	                case 'oldest':
		                $query->set( 'orderby', 'publish_date' );
		                $query->set( 'order', 'ASC' );
		                break;

	                case 'highest_price':
		                $query->set( 'orderby', 'meta_value_num' );
		                $query->set( 'meta_key', 'es_property_price' );
		                $query->set( 'order', 'DESC' );

		                break;

	                case 'featured':
//		                $query_args['orderby'] = 'meta_value_num';
//		                $query_args['meta_key'] = 'es_property_featured';
//		                $query_args['order'] = 'DESC';
                        $query_args['orderby'] = array( 'meta_value_num' => 'DESC', 'ID' => 'DESC' );
                        $query_args['meta_key'] = 'es_property_featured';
		                break;

	                case 'lowest_price':
		                $query->set( 'orderby', 'meta_value_num' );
		                $query->set( 'meta_key', 'es_property_price' );
		                $query->set( 'order', 'ASC' );

		                break;

                    case 'most_popular':
                        $query->query_vars['meta_query'][] = array( 'key' => 'es_property_featured', 'value' => 1 );
                        break;

                    default:
                        $query->set( 'orderby', 'post_date' );
                        $query->set( 'order', 'DESC' );
                }
            }
        }
    }

    /**
     * Return sorting template path.
     *
     * @param string $type
     * @return mixed
     */
    public static function get_template_path( $type = 'list' ) {
        return es_locate_template( sprintf( 'sorting-%s.php', $type ), 'front', 'es_archive_sorting_template' );
    }

    /**
     * Return list of sorting dropdown values.
     *
     * @return mixed
     */
    public static function get_sorting_dropdown_values() {
        return apply_filters( 'es_get_sorting_dropdown_values', array(
            'recent'          => __( 'Most recent',   'es-plugin' ),
            'highest_price'   => __( 'Highest price', 'es-plugin' ),
            'lowest_price'    => __( 'Lowest price',  'es-plugin' ),
            'featured'        => __( 'Featured',  'es-plugin' ),
        ) );
    }
}
