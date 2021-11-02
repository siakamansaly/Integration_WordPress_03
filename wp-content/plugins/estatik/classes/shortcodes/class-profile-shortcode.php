<?php

/**
 * Class Es_Profile_Shortcode.
 */
class Es_Profile_Shortcode extends Es_Shortcode {
	/**
	 * @return string
	 */
	public function get_shortcode_title() {
		return __( 'User profile', 'es-plugin' );
	}
	/**
	 * @inheritdoc
	 */
	public function actions() {

		add_action( 'es_profile_tab_content', array( $this, 'tab_content' ), 10, 1 );

		parent::actions();
	}

	/**
	 * @inheritdoc
	 */
	public function build( $atts = array() ) {

		$atts = wp_parse_args( $atts, $this->get_shortcode_default_atts() );

		$this->actions();

		wp_enqueue_style( 'es-slick-style' );

		ob_start();

		es_load_template( 'shortcodes/profile.php', 'front', null, array(
			'context' => $this,
			'atts' => $atts,
		) );

		return ob_get_clean();
	}

	/**
	 * @return array
	 */
	public function get_shortcode_default_atts() {

		return array(
			'layout' => 'horizontal',
		);
	}

	/**
	 * @inheritdoc
	 */
	public function get_shortcode_name() {
		return 'es_profile';
	}

	/**
	 * @param $tab
	 */
	public function tab_content( $tab ) {

		$options = static::get_tabs();
		$options = $options[ $tab ];

		switch ( $tab ) {
			case 'es-my-profile':
				$template = 'shortcodes/partials/my-profile-tab.php';
				$data = array(
					'options' => $options,
				);
				break;

			case 'es-saved-homes':
				$template = 'shortcodes/partials/saved-homes-tab.php';
				$wishlist = es_get_wishlist_instance();
				$data = array(
					'properties' => $wishlist->get_query(),
					'atts' => array(
						'layout' => '2_col',
					),
					'instance' => $this,
				);
				break;

			case 'es-saved-searches':
				$template = 'shortcodes/partials/saved-searches-tab.php';

				$query = new WP_Query( array(
					'post_type' => Es_Saved_Search::get_post_type_name(),
					'post_status' => 'private',
					'post_author' => get_current_user_id(),
					'author' => get_current_user_id(),
				) );

				$data = array(
					'query' => $query,
				);
				break;
		}

		if ( ! empty( $template ) ) {
			$data = ! empty( $data ) ? $data : array();
			es_load_template( $template, 'front', null, $data );
		}
	}

	/**
	 * @return mixed
	 */
	public static function get_tabs() {

		global $es_settings;

		return apply_filters( 'es_profile_tabs', array(

			'es-saved-searches' => array(
				'label' => __( 'Saved Searches', 'es-plugin' ),
				'icon' => '<i class="fa fa-search" aria-hidden="true"></i>',
				'can_view' => get_current_user_id(),
			),
			'es-saved-homes' => array(
				'label' => __( 'Saved Homes', 'es-plugin' ),
				'icon' => '<i class="fa fa-home" aria-hidden="true"></i>',
				'can_view' => $es_settings->is_wishlist_enabled
			),
			'es-my-profile' => array(
				'label' => __( 'My Profile', 'es-plugin' ),
				'icon' => '<i class="fa fa-user" aria-hidden="true"></i>',
				'can_view' => get_current_user_id() ? es_get_user_entity( get_current_user_id() ) : false,
			),
		) );
	}
}
