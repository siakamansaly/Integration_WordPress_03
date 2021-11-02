<?php

/**
 * Class Es_Property_Slideshow_Shortcode
 */
class Es_Property_Slideshow_Shortcode extends Es_My_Listing_Shortcode
{
	/**
	 * @return string
	 */
	public function get_shortcode_title() {
		return __( 'Slideshow', 'es-plugin' );
	}

	/**
	 * @inheritdoc
	 */
	public function get_shortcode_default_atts()
	{
		$default = array_merge( parent::get_shortcode_default_atts(), array(
			'limit' => 20,
			'show' => null,
			'slider_effect' => 'horizontal',
			'show_arrows' => 0,
			'slides_to_show' => 1,
			'layout' => 'horizontal',
			'margin' => 10,
            'show_labels' => '',
		) );

		return $default;
	}

	/**
	 * @inheritdoc
	 */
	public function build_query_args( $atts )
	{
		$args = parent::build_query_args( $atts );

		if ( $atts['show'] == 'all') {
			$args['limit'] = -1;
			$args['posts_per_page'] = -1;
		} else {
			$args['posts_per_page'] = $atts['limit'];
		}

		unset( $args['paged'] );

		return $args;
	}

	/**
	 * @inheritdoc
	 */
	public function property_loop( $query_args, $atts )
	{
		wp_enqueue_style( 'es-slick-style' );
		wp_enqueue_style( 'es-slick-theme-style' );

		$content = null;
		$posts = array();

		if ( ! empty( $query_args ) ) {
			$query = new WP_Query( $query_args );
			$posts = $query->posts;
		}

		if ( ! empty( $posts ) ) {

			ob_start();

			include es_locate_template( 'widgets/slideshow.php', 'front', 'es_property_slideshow_shortcode_template_path' );

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
		return 'es_property_slideshow';
	}
}
