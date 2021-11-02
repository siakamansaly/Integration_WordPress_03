<?php

/**
 * Class Es_Property_Slidehow_Widget.
 */
class Es_Property_Slideshow_Widget extends Es_Widget
{
	/**
	 * Es_Property_Slideshow_Widget constructor.
	 */
	public function __construct()
	{
		parent::__construct( 'es_property_slideshow', __( 'Estatik Slideshow', 'es-plugin' ) );
	}

	/**
	 * Return layouts of this widget.
	 *
	 * @return array
	 */
	public static function get_layouts()
	{
		return apply_filters( 'es_get_property_slideshow_widget_layouts', array( 'horizontal', 'vertical' ) );
	}

	/**
	 * Return layouts of this widget.
	 *
	 * @return array
	 */
	public static function get_slider_effects() {
		return apply_filters( 'es_get_property_slideshow_widget_effects', array( 'horizontal', 'vertical' ) );
	}

	/**
	 * @inheritdoc
	 */
	public function widget( $args, $instance )
	{
		if ( static::can_render( $instance ) ) {

			if ( ! empty( $instance['filter_data'] ) ) {

				$terms = array();

				foreach ( $instance['filter_data'] as $term_id ) {
					$term = get_term( $term_id );
					$taxonomy = str_replace( 'es_', '', $term->taxonomy );

					$terms[ $taxonomy ][] = $term->name;
				}

				if ( ! empty( $terms ) ) {
					foreach ( $terms as $key => $term_list ) {
						$terms[ $key ] = implode( ',', $term_list );
					}

					$instance = array_merge( $instance, $terms );
				}
			}

			$slideshow = new Es_Property_Slideshow_Shortcode();
			$instance['slides_to_show'] = ! empty( $instance['slides_num'] ) ? $instance['slides_num'] : 1;
			echo ! empty( $args['before_widget'] ) ? $args['before_widget'] : '';

			if ( ! empty( $instance['title'] ) ) {
				echo ! empty( $args['before_title'] ) ? $args['before_title'] : '';
				echo $instance['title'];
				echo ! empty( $args['after_title'] ) ? $args['after_title'] : '';
			}

			echo $slideshow->build( $instance );

			echo ! empty( $args['after_widget'] ) ? $args['after_widget'] : '';
		}
	}

	/**
	 * @return array
	 */
	public static function get_filter_fields_data()
	{
		$data = array();

		$taxonomies = Es_Taxonomy::get_taxonomies_list();

		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				if ( taxonomy_exists( $taxonomy ) ) {
					$taxonomy = get_taxonomy( $taxonomy );

					$data[ $taxonomy->label ] = get_terms( array( 'taxonomy' => $taxonomy->name, 'hide_empty' => false ) );
				}
			}
		}

		return apply_filters( 'es_get_property_slideshow_filter_fields_data', $data );
	}

	/**
	 * Function for register widget.
	 *
	 * @return void
	 */
	public static function register()
	{
		register_widget( 'Es_Property_Slideshow_Widget' );
	}

	/**
	 * @return string
	 */
	protected function get_widget_form_template_path()
	{
		return es_locate_template( 'widgets/es-property-slideshow-widget-form.php', 'admin' );
	}
}

add_action( 'widgets_init', array( 'Es_Property_Slideshow_Widget', 'register' ) );
