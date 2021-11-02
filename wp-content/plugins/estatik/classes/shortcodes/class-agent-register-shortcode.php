<?php

/**
 * Class Es_Register_Shortcode
 */
class Es_Register_Shortcode extends Es_Shortcode
{
	/**
	 * @return string
	 */
	public function get_shortcode_title() {
		return __( 'Register form', 'es-plugin' );
	}
	/**
	 * Function used for build shortcode.
	 * @see add_shortcode
	 *
	 * @param array $atts Shortcode attributes array.
	 *
	 * @return mixed
	 */
	public function build( $atts = array() )
	{
		$can_register = get_option( 'users_can_register' );
		$content = null;
		global $es_settings;

		// If user is not authorized and registration is enabled in wp settings page.
		if ( $can_register && $es_settings->buyers_enabled ) {
		    $data = ! empty( $_POST['es_user'] ) && empty( $_POST['register_success'] ) ? $_POST['es_user'] : array();
		    $data = wp_parse_args( $data, array(
		        'name' => '',
		        'username' => '',
		        'email' => '',
            ) );

			ob_start();

			es_load_template( 'shortcodes/register.php', 'front', 'es_register_shortcode_template', array(
			    'data' => $data
            ) );

			do_action( 'es_shortcode_after', $this->get_shortcode_name() );

			$content = ob_get_clean();
		}

		return $content;
	}

	/**
	 * Return shortcode name.
	 *
	 * @return string
	 */
	public function get_shortcode_name()
	{
		return 'es_register';
	}
}
