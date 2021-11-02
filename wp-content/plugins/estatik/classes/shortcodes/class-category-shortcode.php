<?php

/**
 * Class Es_Category_Shortcode
 */
class Es_Category_Shortcode extends Es_My_Listing_Shortcode
{
	/**
	 * @return string
	 */
	public function get_shortcode_title() {
		return __( 'Properties By Category', 'es-plugin' );
	}

    /**
     * @inheritdoc
     */
    public function get_shortcode_name()
    {
        return 'es_category';
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

        if ( empty( $atts['category'] ) && empty( $atts['type'] ) && empty( $atts['status'] ) && empty( $atts['rent_period'] ) ) {
            $atts['prop_id'] = array( -1 );
        }

        return $atts;
    }
}
