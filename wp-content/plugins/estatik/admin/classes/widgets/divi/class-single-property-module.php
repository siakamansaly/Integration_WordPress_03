<?php

/**
 * Class Es_Divi_Single_Property_Builder_Module.
 */
class Es_Divi_Single_Property_Builder_Module extends ET_Builder_Module {

    /**
     * @return void
     */
    public function init() {
        $this->name = esc_html__( 'Single Property Page', 'es-plugin' );
        $this->plural = esc_html__( 'Single Property Page', 'es-plugin' );
        $this->slug = 'es_single_property_page';
        $this->vb_support = 'on';
        $this->post_types = array_merge( et_builder_get_builder_post_types(), array( 'properties' ) );
    }

    public function get_fields() {
        return parent::get_fields();
    }

    /**
     * @param array $unprocessed_props
     * @param null $content
     * @param string $render_slug
     * @return bool|string|void|null
     */
    public function render( $unprocessed_props, $content = null, $render_slug ) {
        return do_shortcode( '[es_single]' );
    }
}

new Es_Divi_Single_Property_Builder_Module();