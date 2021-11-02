<?php

use Elementor\Widget_Base;

/**
 * Class Elementor_Es_Base_Widget.
 */
abstract class Elementor_Es_Base_Widget extends Widget_Base {

    /**
     * @return array|string[]
     */
    public function get_categories() {
        return array( 'estatik-category' );
    }

    /**
     * Elementor_Es_Base_Widget constructor.
     * @param array $data
     * @param null $args
     * @throws Exception
     */
    public function __construct( $data = array(), $args = null ) {
        parent::__construct( $data, $args );

        wp_register_script( 'es-script-handle', ES_ADMIN_CUSTOM_SCRIPTS_URL . 'elementor.js', array( 'es-front-script', 'elementor-frontend' ), '1.0.0', true );
    }

    public function get_script_depends() {
        return [ 'es-script-handle' ];
    }
}
