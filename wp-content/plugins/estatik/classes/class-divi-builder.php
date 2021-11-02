<?php

/**
 * Class Es_Divi_Init
 */
class Es_Divi_Init {

    /**
     * @return void
     */
    public static function init() {
        add_action( 'et_builder_framework_loaded', array( 'Es_Divi_Init', 'load_modules' ) );
        add_filter( 'et_fb_load_raw_post_content', array( 'Es_Divi_Init', 'load_raw_post_content' ), 10, 2 );
    }

    /**
     * @param $post_content
     * @param $post_id
     * @return string
     */
    public static function load_raw_post_content( $post_content, $post_id ) {
        $post = get_post( absint( $post_id ) );

        if ( ! ( $post instanceof WP_Post ) || 'properties' !== $post->post_type ) {
            return $post_content;
        }

        if ( has_shortcode( $post_content, 'es_single_property_page' ) && ! empty( $post_content ) ) {
            return $post_content;
        }

        return es_et_builder_estatik_get_initial_property_content();
    }

    /**
     * @return void
     */
    public static function load_modules() {
        if ( class_exists( 'ET_Builder_Module' ) ) {
            require_once ES_PLUGIN_PATH . '/admin/classes/widgets/divi/class-single-property-module.php';
        }
    }
}

Es_Divi_Init::init();
