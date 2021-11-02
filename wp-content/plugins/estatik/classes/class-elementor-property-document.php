<?php

/**
 * Class EstatikProperty.
 */
class Es_Elementor_Property_Document extends \ElementorPro\Modules\ThemeBuilder\Documents\Single_Base {

    /**
     * @return array
     */
    public static function get_properties() {
        $properties = parent::get_properties();

        $properties['location'] = 'single';
        $properties['condition_type'] = 'properties';

        return $properties;
    }

    /**
     * @return string
     */
    protected static function get_site_editor_type() {
        return 'properties';
    }

    /**
     * @return string|void
     */
    public static function get_title() {
        return __( 'Single Property', 'es-plugin' );
    }
}
