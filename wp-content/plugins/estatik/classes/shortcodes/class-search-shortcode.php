<?php

/**
 * Class Es_Search_Shortcode.
 */
class Es_Search_Shortcode extends Es_My_Listing_Shortcode {

	/**
	 * @return string
	 */
	public function get_shortcode_title() {
		return __( 'Search results', 'es-plugin' );
	}

    /**
     * @inheritDoc
     */
    public function build( $atts = array() ) {

	    global $properties_shortcode_counter;
	    $properties_shortcode_counter++;

	    // Set Unique Loop Identifier.
	    $this->uid = $properties_shortcode_counter;

        $atts = shortcode_atts( $this->get_shortcode_default_atts(), $atts );

        $query = $this->build_query_args( $atts );

        $properties = new WP_Query( $query );
	    $properties->properties_loop_identifier = $this->uid;
	    $properties->query_vars['properties_loop_identifier'] = $this->uid;

        ob_start();

        include es_locate_template( 'shortcodes/my-listing.php', 'front', 'es_get_my_listings_template_path' );

        return ob_get_clean();
    }

    /**
     * @param $atts
     *
     * @return array
     */
    public function build_query_args( $atts ) {

        $query = parent::build_query_args( $atts );

        if ( ! empty( $_GET['es_search'] ) ) {

        	$search_args = $_GET['es_search'];

	        foreach ( $search_args as $key => $value ) {

		        if ( is_array( $search_args[ $key ] ) ) {
			        $search_args[ $key ] = array_map( 'sanitize_text_field', $search_args[ $key ] );
		        } else {
			        $search_args[ $key ] = sanitize_text_field( $value );
		        }
	        }

            $temp = Es_Search_Widget::build_query( new WP_Query(), $search_args );
            $query = array_merge_recursive( $temp->query_vars, $query );
        }

        return apply_filters( 'es_search_shortcode_query_args', $query );
    }

    /**
     * @inheritDoc
     */
    public function get_shortcode_name() {
        return 'es_search';
    }

    /**
     * @return array
     */
    public function get_shortcode_default_atts()
    {
        global $es_settings;

        $atts = array(
            // list, 2_col, 3_col
            'layout' => $es_settings->listing_layout,
            'posts_per_page' => $es_settings->properties_per_page,
            // recent, highest_price, lowest_price, most_popular
            'sort' => 'recent',
            // Show filter dropdown with sort values.
            'show_filter' => 1,
        );

        return apply_filters( $this->get_shortcode_name() . '_default_atts', $atts );
    }
}
