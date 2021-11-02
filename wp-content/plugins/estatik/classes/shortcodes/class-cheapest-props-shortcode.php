<?php

/**
 * Class Es_Cheapest_Props_Shortcode
 */
class Es_Cheapest_Props_Shortcode extends Es_My_Listing_Shortcode
{
	/**
	 * @return string
	 */
	public function get_shortcode_title() {
		return __( 'Cheapest Properties', 'es-plugin' );
	}

    /**
     * @inheritdoc
     */
    public function get_shortcode_name()
    {
        return 'es_cheapest_props';
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
        $atts['sort'] = 'lowest_price';

        return $atts;
    }
}
