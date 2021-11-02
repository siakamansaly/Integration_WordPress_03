<?php

/**
 * Class Es_Demo_Content_Page.
 */
class Es_Demo_Content_Page extends Es_Object {

	/**
	 * @return void
	 */
	public static function render() {
		$step = sanitize_text_field(filter_input( INPUT_GET, 'step' ) );

		wp_enqueue_script( 'es-demo-script', ES_ADMIN_CUSTOM_SCRIPTS_URL . 'demo.js', array( 'jquery' ) );

		switch ( $step ) {
			case 'demo':
            case 'finished':
				$path = static::get_step_template( $step );
				break;

			default:
				$step = 'start';
				$path = static::get_step_template( $step );
		}

		require_once $path;
	}

	/**
	 * @param $step
	 * @return string
	 */
	public static function get_step_template( $step ) {
		return es_locate_template( 'demo-content/' . $step . '-' . 'step.php', 'admin', 'es_demo_content_step_template_path' );
	}

	/**
	 * @param $template_name
	 * @return string
	 */
	public static function get_partials_template( $template_name ) {
        return es_locate_template( "demo-content/partials/{$template_name}.php", 'admin', 'es_demo_content_step_template_path' );
	}
}
