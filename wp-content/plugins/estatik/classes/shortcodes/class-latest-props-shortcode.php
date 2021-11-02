<?php

/**
 * Class Es_Latest_Props_Shortcode
 */
class Es_Latest_Props_Shortcode extends Es_My_Listing_Shortcode
{
	/**
	 * @return string
	 */
	public function get_shortcode_title() {
		return __( 'Latest Properties', 'es-plugin' );
	}

    /**
     * @return string
     */
    public function get_shortcode_name()
    {
        return 'es_latest_props';
    }

    /**
     * Merge shortcode attributes (default / input).
     *
     * @param $atts
     * @return array
     */
    public function merge_shortcode_atts( $atts )
    {
        $atts = parent::merge_shortcode_atts( $atts );
        $atts['sort'] = 'newest';

        return $atts;
    }
}
