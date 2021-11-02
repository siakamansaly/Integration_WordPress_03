<?php

/**
 * Class Es_Restore_Password_Shortcode
 */
class Es_Restore_Password_Shortcode extends Es_Shortcode {

	/**
	 * @return string
	 */
	public function get_shortcode_title() {
		return __( 'Reset password', 'es-plugin' );
	}

	/**
	 * Function used for build shortcode.
	 * @see add_shortcode
	 *
	 * @param array $atts Shortcode attributes array.
	 *
	 * @return mixed
	 */
	public function build($atts = array()) {
		ob_start();
		include es_locate_template( 'shortcodes/reset.php', 'front', 'es_restore_pwd_template_path' );
		do_action( 'es_shortcode_after', $this->get_shortcode_name() );
		return ob_get_clean();
	}

	/**
	 * Return shortcode name.
	 *
	 * @return string
	 */
	public function get_shortcode_name() {
		return 'es_reset_pwd';
	}
}
