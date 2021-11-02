<?php

/**
 * Class Es_Login_Shortcode
 */
class Es_Login_Shortcode extends Es_Shortcode {
	/**
	 * @return string
	 */
	public function get_shortcode_title() {
		return __( 'Login form', 'es-plugin' );
	}

    /**
     * @return array|int[]
     */
	public function get_shortcode_default_atts() {
	    global $es_settings;

	    return array(
            'enable_facebook' => $es_settings->enable_facebook_auth,
            'enable_google' => $es_settings->enable_google_auth,
        );
    }

	/**
	 * Function used for build shortcode.
	 * @see add_shortcode
	 *
	 * @param array $atts Shortcode attributes array.
	 *
	 * @return mixed
	 */
	public function build( $atts = array() ) {

        $atts = shortcode_atts( $this->get_shortcode_default_atts(), $atts );

		ob_start();

		$hook = 'es_login_shortcode_template_path';
		include es_locate_template(  'shortcodes/login.php', 'front', $hook );
		do_action( 'es_shortcode_after', $this->get_shortcode_name() );

		return ob_get_clean();
	}

	/**
	 * Return shortcode name.
	 *
	 * @return string
	 */
	public function get_shortcode_name() {

		return 'es_login';
	}
}
