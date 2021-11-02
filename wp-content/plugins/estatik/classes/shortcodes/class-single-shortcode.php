<?php

/**
 * Class Es_Single_Shortcode
 */
class Es_Single_Shortcode extends Es_Shortcode
{
	/**
	 * @return string
	 */
	public function get_shortcode_title() {
		return __( 'Single property', 'es-plugin' );
	}

    /**
     * @inheritdoc
     */
    public function build( $attr = array() ) {
    	$attr = shortcode_atts( array(
    		'id' => get_the_ID(),
	    ), $attr );

        $content = '';

        if ( ! empty( $attr['id'] ) ) {
            wp_enqueue_style( 'es-front-single-style' );
            wp_enqueue_script( 'es-front-single-script' );
            wp_localize_script( 'es-front-single-script', 'Estatik', Estatik::register_js_variables() );

            ob_start();
            $entity = es_get_property( $attr['id'] );

            $query = new WP_Query( array(
                'post_type' => $entity::get_post_type_name(),
                'p' => $attr['id']
            ) );

            if ( is_singular( $entity::get_post_type_name() ) ) {
                es_load_template( 'content-single.php', 'front', 'es_single_template_path' );
            } else {
                if ( $query->have_posts() ) {
                    while( $query->have_posts() ) {
                        $query->the_post();
                        es_load_template( 'content-single.php', 'front', 'es_single_template_path' );
                    }
                }
                wp_reset_postdata();
            }

            $content = ob_get_clean();
        }

        return $content;
    }

    /**
     * @inheritdoc
     */
    public function get_shortcode_name() {
        return 'es_single';
    }
}
