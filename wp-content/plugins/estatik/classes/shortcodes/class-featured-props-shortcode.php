<?php

/**
 * Class Es_Featured_Props_Shortcode
 */
class Es_Featured_Props_Shortcode extends Es_My_Listing_Shortcode
{
	/**
	 * @return string
	 */
	public function get_shortcode_title() {
		return __( 'Featured Properties', 'es-plugin' );
	}

    /**
     * @inheritdoc
     */
    public function get_shortcode_name()
    {
        return 'es_featured_props';
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
        $atts['sort'] = 'most_popular';

        return $atts;
    }
}
