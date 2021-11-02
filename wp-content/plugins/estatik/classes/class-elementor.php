<?php

use \Elementor\Plugin;

/**
 * Class Es_Elementor_Init.
 */
class Es_Elementor_Init {

    /**
     * Initialize estatik elementor integration.
     *
     * @return void
     */
    public static function init() {
        add_action( 'elementor/widgets/widgets_registered', array( 'Es_Elementor_Init', 'register_widgets' ) );
        add_action( 'elementor/elements/categories_registered', array( 'Es_Elementor_Init', 'register_category' ) );
        add_filter( 'elementor/widgets/black_list', array( 'Es_Elementor_Init', 'widgets_black_list' ) );
        add_action( 'elementor/documents/register', array( 'Es_Elementor_Init', 'register_document_type' ) );
        add_action( 'elementor/db/before_save', array( 'Es_Elementor_Init', 'save_temp_post_content' ) );
    }

    /**
     * @return void
     */
    public static function save_temp_post_content() {
        $post_id = filter_input( INPUT_POST, 'editor_post_id' );

        if ( $post_id ) {
            $post = get_post( $post_id );
            $property = es_get_property( $post_id );

            if ( ! empty( $post ) && $post->post_type == 'properties' ) {
                $content_copied = get_post_meta( $post_id, 'es_post_content_copied', true );
                $is_valid_content = stristr( $post->post_content, '[es_single' ) === false && stristr( $post->post_content, 'es-single' ) === false;

                if ( ! $content_copied && empty( $property->alternative_description ) && $is_valid_content ) {
                    $property->save_field_value( 'alternative_description', $post->post_content );
                    update_post_meta( $post_id, 'es_post_content_copied', 1 );
                }
            }
        }
    }

    /**
     * @param $manager Elementor\Core\Documents_Manager
     */
    public static function register_document_type( $manager ) {

        if ( class_exists( 'ElementorPro\Modules\ThemeBuilder\Documents\Single_Base' ) ) {
            require_once ES_PLUGIN_PATH . '/classes/class-elementor-property-document.php';
        }

        if ( class_exists( 'Es_Elementor_Property_Document' ) ) {
            $manager->register_document_type( 'properties', 'Es_Elementor_Property_Document' );
        }
    }

    /**
     * Disable default estatik widgets for elementor.
     *
     * @param $list
     * @return array
     */
    public static function widgets_black_list( $list ) {
        $list[] = 'Es_Property_Slideshow_Widget';
        $list[] = 'Es_Request_Widget';
        $list[] = 'Es_Search_Widget';

        return $list;
    }

    /**
     * Register elementor widgets.
     *
     * @return void
     * @throws Exception
     */
    public static function register_widgets() {
        if ( class_exists( 'Elementor\Widget_Base' ) ) {
            require_once trailingslashit( ES_PLUGIN_PATH . '/admin/classes/widgets/elementor/' ) . 'class-elementor-base.php';
            require_once trailingslashit( ES_PLUGIN_PATH . '/admin/classes/widgets/elementor/' ) . 'class-elementor-slideshow-widget.php';
            require_once trailingslashit( ES_PLUGIN_PATH . '/admin/classes/widgets/elementor/' ) . 'class-elementor-request-widget.php';
            require_once trailingslashit( ES_PLUGIN_PATH . '/admin/classes/widgets/elementor/' ) . 'class-elementor-search-widget.php';
            require_once trailingslashit( ES_PLUGIN_PATH . '/admin/classes/widgets/elementor/' ) . 'class-elementor-listings-widget.php';

            Plugin::instance()->widgets_manager->register_widget_type( new Elementor_Es_Slideshow_Widget() );
            Plugin::instance()->widgets_manager->register_widget_type( new Elementor_Es_Request_Widget() );
            Plugin::instance()->widgets_manager->register_widget_type( new Elementor_Es_Search_Widget() );
            Plugin::instance()->widgets_manager->register_widget_type( new Elementor_Es_Listings_Widget() );
        }
    }

    /**
     * Register new Estatik category.
     *
     * @param $elements_manager \Elementor\Elements_Manager
     */
    public static function register_category( $elements_manager ) {
        $elements_manager->add_category(
            'estatik-category',
            array(
                'title' => _x( 'Estatik', 'Elementor widgets category name', 'es-plugin' ),
            )
        );
    }
}

Es_Elementor_Init::init();
