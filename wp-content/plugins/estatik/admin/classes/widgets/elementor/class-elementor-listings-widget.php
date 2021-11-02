<?php

use Elementor\Controls_Manager;

/**
 * Class Es_Elementor_Search_Form_Widget.
 */
class Elementor_Es_Listings_Widget extends Elementor_Es_Base_Widget {
    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'es-listings-widget';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return _x( 'Estatik Listings', 'widget name', 'es-plugin' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'es-icon es-icon_listings';
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function _register_controls() {
        global $es_settings;

        $defaults = array(
            'layout' => $es_settings->listing_layout,
            'posts_per_page' => $es_settings->properties_per_page,
            'sort' => 'recent',
            'show_filter' => null,
        );

        $this->start_controls_section(
            'section_content', array( 'label' => _x( 'Content', 'Elementor widget section', 'es-plugin' ), )
        );

        $this->add_control( 'layout', array(
            'label' => __( 'Layout', 'es-plugin' ),
            'type' => Controls_Manager::SELECT,
            'default' => $defaults['layout'],
            'options' => $es_settings::get_setting_values( 'listing_layout' ),
        ) );

        $this->add_control( 'show_filter', array(
            'label' => __( 'Show filter', 'es-plugin' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => $defaults['show_filter'] ? 'yes' : $defaults['show_filter'],
        ) );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_filter', array( 'label' => _x( 'Filters', 'Elementor widget section', 'es-plugin' ), )
        );

        $this->add_control( 'posts_per_page', array(
            'label' => __( 'Properties per page', 'es-plugin' ),
            'type' => Controls_Manager::NUMBER,
            'min' => 0,
            'default' => $defaults['posts_per_page'],
        ) );

        $this->add_control( 'prop_id', array(
            'label' => __( 'Properties IDs', 'es-plugin' ),
            'type' => Controls_Manager::TEXT,
        ) );

        $this->add_control( 'sort', array(
            'label' => __( 'Default sort', 'es-plugin' ),
            'type' => Controls_Manager::SELECT,
            'default' => $defaults['sort'],
            'options' => Es_Archive_Sorting::get_sorting_dropdown_values(),
        ) );

        foreach ( Es_Taxonomy::get_taxonomies_list() as $taxonomy ) {
            $tax = new Es_Taxonomy( $taxonomy );

            if ( 'es_labels' == $taxonomy ) {
                $terms_keys = get_terms( array( 'taxonomy' => $taxonomy, 'fields' => 'id=>slug', 'hide_empty' => false ) );
                $terms_values = get_terms( array( 'taxonomy' => $taxonomy, 'fields' => 'names', 'hide_empty' => false ) );
                if ( is_array( $terms_keys ) && is_array( $terms_values ) ) {
                    $terms = $terms_keys && $terms_values ? array_combine( $terms_keys, $terms_values ) : array();
                } else {
                    $terms = array();
                }
            } else {
                $terms = get_terms( array( 'taxonomy' => $taxonomy, 'fields' => 'names', 'hide_empty' => false ) );
                if ( is_array( $terms ) && ! is_wp_error( $terms ) ) {
                    $terms = $terms ? array_combine( $terms, $terms ) : array();
                } else {
                    $terms = array();
                }
            }

            $this->add_control( str_replace( 'es_', '', $taxonomy ), array(
                'label' => __( $tax->get_name(), 'es-plugin' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => 'multiple',
                'options' => $terms,
            ) );
        }

        foreach ( array( 'country', 'state', 'province', 'city', 'neighborhood' ) as $location ) {
            $finfo = Es_Property::get_field_info( $location );
            if ( ! empty( $finfo ) ) {
                $location_types = $finfo['components_types'];
                $locations = array();
                $location_type = null;

                foreach ( $location_types as $location_type ) {
                    $locations_list = ES_Address_Components::get_component_list( $location_type );
                    $locations_list = $locations_list ? wp_list_pluck( $locations_list, 'long_name', 'id' ) : array();
                    $locations = array_replace( $locations, $locations_list );
                }

                $this->add_control( $location, array(
                    'label' => __( ES_Address_Components::get_label_by_type( $location ), 'es-plugin' ),
                    'type' => Controls_Manager::SELECT2,
                    'multiple' => 'multiple',
                    'options' => $locations,
                ) );
            }
        }

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render() {
        $shortcode = new Es_My_Listing_Shortcode();
        echo $shortcode->build( $this->get_settings_for_display() );
    }
}
