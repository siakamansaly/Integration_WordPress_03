<?php

/**
 * Base class for estatik shortcodes Es_Shortcode.
 */
abstract class Es_Shortcode extends Es_Object
{
    /**
     * Return shortcode default attributes.
     *
     * @return array
     */
    public function get_shortcode_default_atts() {
        return array();
    }

    /**
     * Function used for build shortcode.
     * @see add_shortcode
     *
     * @param array $atts Shortcode attributes array.
     *
     * @return mixed
     */
    abstract public function build( $atts = array() );

    /**
     * Return shortcode name.
     *
     * @return string
     */
    abstract public function get_shortcode_name();

    abstract public function get_shortcode_title();
}
